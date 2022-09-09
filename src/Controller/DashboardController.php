<?php

declare(strict_types=1);

namespace Blog\Controller;

use Blog\Entity\Comment;
use Blog\Entity\Post;
use Blog\Entity\User;
use Blog\Form\FormHandler;
use Blog\ORM\EntityManager;
use Blog\Repository\CommentRepository;
use Blog\Repository\PostRepository;
use Blog\Repository\UserRepository;
use Blog\Router\Exceptions\ResourceNotFound;
use Blog\Router\Exceptions\RouteNotFoundException;
use Blog\Router\Router;
use Blog\Security\Authenticator;
use Blog\Service\FileUploader;
use DateTime;
use Gumlet\ImageResize;
use Gumlet\ImageResizeException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;
use Soundasleep\Html2Text;
use Soundasleep\Html2TextException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Throwable;

class DashboardController extends AbstractController
{
    /**
     * @throws Exception\UnauthorizedException
     */
    public function index(
        UserRepository $userRepository,
        CommentRepository $commentRepository,
        PostRepository $postRepository,
    ): Response {
        $this->denyAccessUnlessIsAdmin();

        return $this->render('pages/back/index.html.twig', [
            'nbUsers'            => $userRepository->countAll(),
            'nbDisabledUsers'    => $userRepository->countUserAwaitingValidation(),
            'nbCommentsAwaiting' => $commentRepository->countAwaitingValidation(),
            'nbComments'         => $commentRepository->countAll(),
            'nbPosts'            => $postRepository->countAll(),
        ]);
    }

    /**
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception\UnauthorizedException
     * @throws RouteNotFoundException
     * @throws ImageResizeException
     */
    public function createPost(
        Request $request,
        FormHandler $formHandler,
        Authenticator $auth,
        FileUploader $fileUploader,
        EntityManager $entityManager,
        Router $router,
        string $uploadDir,
    ): Response {
        $this->denyAccessUnlessIsAdmin();

        $post = new Post();

        $post->setUserId($auth->currentUserId());

        $form = $formHandler->loadFromRequest($request, $post);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $post->getFile();
            if ($file) {
                $filename = $fileUploader->upload($post->getFile());
                $post->setFilename($filename);

                // Generate image thumbnail
                $thumbnail = new ImageResize($uploadDir . '/' . $filename);
                $thumbnail->resizeToHeight(400);
                $thumbnail->save(filename: $uploadDir . '/thumbs/' . $filename);
            }

            $entityManager->add($post);
            $entityManager->flush();

            /** @var Session $session */
            $session = $request->getSession();
            $session->getFlashBag()->add('success', 'Article publié');

            return $this->redirect($router->generateUri('post', ['slug' => $post->getSlug()]));
        }

        return $this->render('pages/back/form_post.html.twig', ['form' => $form]);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws Exception\UnauthorizedException
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     * @throws ResourceNotFound
     * @throws ImageResizeException
     * @throws \Exception
     */
    public function updatePost(
        string $id,
        string $uploadDir,
        PostRepository $postRepository,
        UserRepository $userRepository,
        EntityManager $entityManager,
        FormHandler $formHandler,
        Request $request,
        FileUploader $fileUploader,
    ): Response {
        $this->denyAccessUnlessIsAdmin();
        /** @var ?Post $post */
        $post         = $postRepository->find($id);
        $tempFilename = $post->getFilename();

        if (! $post) {
            throw new ResourceNotFound('Article introuvable');
        }

        $owner   = $userRepository->find($post->getUserId());
        $authors = array_filter(
            $userRepository->findAllBy(['is_admin' => 1]),
            fn($user) => $user->getId() !== $post->getUserId()
        );

        $form = $formHandler->loadFromRequest($request, $post, true);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($post->getFile()) {
                $filename = $fileUploader->replace($tempFilename, $post->getFile());
                $post->setFilename($filename);

                // Generate image thumbnail
                $thumbnail = new ImageResize($uploadDir . '/' . $filename);
                $thumbnail->resizeToHeight(400);
                $thumbnail->save(filename: $uploadDir . '/thumbs/' . $filename);
            }

            $post->setUpdatedAt(new DateTime());
            $post->setUserId($form->get('author'));

            $entityManager->update($post);
            $entityManager->flush();

            /** @var Session $session */
            $session = $request->getSession();
            $session->getFlashBag()->add('success', 'Article modifié');

            return $this->redirect($request->headers->get('referer'));
        }

        return $this->render(
            'pages/back/form_edit_post.html.twig',
            [
                'post'    => $post,
                'form'    => $form,
                'authors' => $authors,
                'owner'   => $owner,
            ]
        );
    }

    /**
     * @throws Exception\UnauthorizedException
     * @throws ReflectionException
     */
    public function showComments(
        CommentRepository $commentRepository,
        UserRepository $userRepository,
        PostRepository $postRepository
    ): Response {
        $this->denyAccessUnlessIsAdmin();

        $comments = $commentRepository->findAll();

        $users = [];
        $posts = [];
        /** @var Comment $comment */
        foreach ($comments as $comment) {
            $userId = $comment->getUserId();
            $comment->setUser($users[$userId] ?? $users[$userId] = $userRepository->find($userId));

            $postId = $comment->getPostId();
            $comment->setPost($posts[$postId] ?? $posts[$postId] = $postRepository->find($postId));
        }

        return $this->render('pages/back/comments.html.twig', ['comments' => $comments]);
    }

    /**
     * @throws Exception\UnauthorizedException
     */
    public function createComment(Request $request): Response
    {
        $this->denyAccessUnlessIsAdmin();

        return $this->render('pages/back/form_post.html.twig');
    }

    /**
     * @throws Exception\UnauthorizedException
     */
    public function updateComment(int $id): Response
    {
        $this->denyAccessUnlessIsAdmin();
        $comment = new Post();

        return $this->render('pages/back/form_post.html.twig', ['comment' => $comment]);
    }

    /**
     * @throws Exception\UnauthorizedException
     * @throws ReflectionException
     */
    public function showUsers(
        UserRepository $userRepository
    ): Response {
        $this->denyAccessUnlessIsAdmin();

        $users = $userRepository->findAll();

        return $this->render('pages/back/users.html.twig', ['users' => $users]);
    }

    /**
     * @throws ReflectionException
     * @throws ResourceNotFound
     * @throws Exception\UnauthorizedException
     */
    public function deleteUser(
        string $id,
        UserRepository $userRepository,
        EntityManager $entityManager,
        Request $request,
    ): Response {
        $this->denyAccessUnlessIsAdmin();

        /** @var ?User $user */
        $user = $userRepository->find($id);

        if (! $user) {
            throw new ResourceNotFound('Utilisateur introuvable');
        }

        $entityManager->delete($user);
        $entityManager->flush();

        /** @var Session $session */
        $session = $request->getSession();
        $session->getFlashBag()->add('success', 'Utilisateur ' . $user->getUsername() . ' supprimé');

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @throws Exception\UnauthorizedException
     * @throws ResourceNotFound
     * @throws ReflectionException
     * @throws Html2TextException
     * @throws RouteNotFoundException
     */
    public function toggleUser(
        string $id,
        UserRepository $userRepository,
        EntityManager $entityManager,
        Request $request,
        MailerInterface $mailer,
        Router $router
    ): Response {
        $this->denyAccessUnlessIsAdmin();

        /** @var ?User $user */
        $user = $userRepository->find($id);

        if (! $user) {
            throw new ResourceNotFound('Utilisateur introuvable');
        }

        $user->setEnabled((int) ! $user->getEnabled());

        $entityManager->update($user);
        $entityManager->flush();

        $status = ($user->getEnabled()) ? 'activé' : 'désactivé';

        $loginUrl = $request->getSchemeAndHttpHost() . $router->generateUri('login');

        $emailContent = $this->renderView(
            'mails/toggle_user.html.twig',
            [
                'url'    => $loginUrl,
                'status' => $status,
            ],
            false
        );

        $email = (new Email())
            ->from($_ENV['MAILER_SENDER'])
            ->to($user->getEmail())
            ->priority(Email::PRIORITY_NORMAL)
            ->subject("Compte $status")
            ->text(strip_tags(Html2Text::convert($emailContent)))
            ->html(nl2br($emailContent));

        try {
            $mailer->send($email);
        } catch (Throwable $exception) {
            /** @var Session $session */
            $session = $request->getSession();
            $session->getFlashBag()->add('danger', "Impossible d'envoyer la notification mail");
        }

        /** @var Session $session */
        $session = $request->getSession();
        $session->getFlashBag()->add(
            'success',
            'Utilisateur ' . $user->getUsername() . ' ' . $status
        );

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @throws ReflectionException
     * @throws Exception\UnauthorizedException
     */
    public function showPosts(
        PostRepository $postRepository,
        UserRepository $userRepository,
        CommentRepository $commentRepository
    ): Response {
        $this->denyAccessUnlessIsAdmin();

        $posts = $postRepository->findAll();

        $users = [];
        /** @var Post $post */
        foreach ($posts as $post) {
            $userId = $post->getUserId();
            $post->setUser($users[$userId] ?? $users[$userId] = $userRepository->find($userId));
            /** @var Comment[] $comments */
            $comments = $commentRepository->findAllBy(['post_id' => $post->getId(), 'enabled' => true]);
            $post->setComments($comments);
        }

        return $this->render('pages/back/posts.html.twig', ['posts' => $posts]);
    }

    /**
     * @throws ReflectionException
     * @throws ResourceNotFound
     * @throws Exception\UnauthorizedException
     */
    public function deletePost(
        string $id,
        PostRepository $postRepository,
        EntityManager $entityManager,
        Request $request,
        FileUploader $fileUploader
    ): Response {
        $this->denyAccessUnlessIsAdmin();

        /** @var ?Post $post */
        $post = $postRepository->find($id);

        if (! $post) {
            throw new ResourceNotFound('Utilisateur introuvable');
        }

        $entityManager->delete($post);
        $entityManager->flush();

        if ($post->getFilename()) {
            $fileUploader->remove($post->getFilename());
            $fileUploader->remove('/thumbs/' . $post->getFilename());
        }

        /** @var Session $session */
        $session = $request->getSession();
        $session->getFlashBag()->add('success', 'Article supprimé');

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @throws ReflectionException
     * @throws Exception\UnauthorizedException
     * @throws ResourceNotFound
     */
    public function toggleComment(
        string $id,
        CommentRepository $commentRepository,
        EntityManager $entityManager,
        Request $request,
    ): Response {
        $this->denyAccessUnlessIsAdmin();

        /** @var ?Comment $comment */
        $comment = $commentRepository->find($id);

        if (! $comment) {
            throw new ResourceNotFound('Utilisateur introuvable');
        }

        $comment->setEnabled((int) ! $comment->getEnabled());

        $entityManager->update($comment);
        $entityManager->flush();

        $status = ($comment->getEnabled()) ? 'activé' : 'désactivé';

        /** @var Session $session */
        $session = $request->getSession();
        $session->getFlashBag()->add(
            'success',
            "Commentaire $status"
        );

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @throws ReflectionException
     * @throws ResourceNotFound
     * @throws Exception\UnauthorizedException
     */
    public function deleteComment(
        string $id,
        CommentRepository $commentRepository,
        EntityManager $entityManager,
        Request $request,
    ): Response {
        $this->denyAccessUnlessIsAdmin();

        /** @var ?Comment $comment */
        $comment = $commentRepository->find($id);

        if (! $comment) {
            throw new ResourceNotFound('Utilisateur introuvable');
        }

        $entityManager->delete($comment);
        $entityManager->flush();

        /** @var Session $session */
        $session = $request->getSession();
        $session->getFlashBag()->add('success', 'Commentaire supprimé');

        return $this->redirect($request->headers->get('referer'));
    }
}
