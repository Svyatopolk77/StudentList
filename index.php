<?php 
session_start();

require __DIR__.'/vendor/autoload.php';

use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface; 
use Pst\Http\Server\RequestHandlerInterface;
use Twig\Loader\FileSystemLoader;

use Twig\Environment;
use App\Database;
use App\Auth;
use App\AuthException;
use App\Session;
use App\StudentModel;

$loader=new FileSystemLoader('templates');
$twig=new Environment($loader);


$auth=new Auth($db);
$studentmodel=new StudentModel();
$app=AppFactory::create();

$session=new Session();
// $sessionMiddleware=function(ServerRequestInterface $request,RequestHandlerInterface $handler) use($session){
//     $session->start();
//     $response=$handler->handle($request);
//     $session->save();
//     return $response;
// };
// $app->add($sessionMiddleware);

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);

$app->get('/signup',function(ServerRequestInterface $request, ResponseInterface $response)use ($twig,$session){
    $body=$twig->render('signup.twig',[
        'message'=>$session->flush('message')
    ]);
    $response->getBody()->write($body);
    return $response;
});
$app->get('/',function (ServerRequestInterface $request,ResponseInterface $response,$args) use ($twig,$studentmodel,$session){
    $page= $request->getQueryParams()!=null?$request->getQueryParams()['page']:1;
    if (!empty($session->getData('user'))){
        $profile=$studentmodel->getStudentById($session->getData('user')['id']);

    }
    $body=$twig->render('home.twig',[
        'profile'=>$profile,
        'students'=>$studentmodel->getAllStudents($page,5,'egescore','DESC')
        // 'page'=>$page
    ]);
    $response->getBody()->write($body);
    return $response;
});
$app->get('/signin',function(ServerRequestInterface $request, ResponseInterface $response) use ($twig,$session){
    $body=$twig->render('signin.twig',[
        'message'=>$session->flush('message')
    ]);
    $response->getBody()->write($body);
    return $response;
});
$app->post('/signin-post',function (ServerRequestInterface $request, ResponseInterface $response) use($auth,$session,$studentmodel){
    $params=(array) $request->getParsedBody();
    $profile=$studentmodel->getStudentByName($params['name'],$params['surname']);
    try {
        $auth->signIn($params);
        $session->setData('user',$profile);


    } catch (Exception $e) {
        $session->setData('message',$e->getMessage());
        return $response->withHeader('Location','/signin')->withStatus(302);
    }
   
    return $response->withHeader('Location','/')->withStatus(302);
});
$app->post('/signup-post',function (ServerRequestInterface $request, ResponseInterface $response) use($auth,$session,$studentmodel){
    $params=(array) $request->getParsedBody();
    try {
        $auth->signUp($params);
        $studentmodel->saveStudent($params['name'],$params['surname'],$params['password'],$params['sex'],$params['groupnum'],$params['email'],$params['egescore']);
        

    } catch (Exception $e) {
        $session->setData('message',$e->getMessage());
        return $response->withHeader('Location','/signup')->withStatus(302);
    }
   
    return $response->withHeader('Location','/signin')->withStatus(302);
});
$app->post('/logout',function(ServerRequestInterface $request,ResponseInterface $response) use($session){
    $session->unset('user');
    return $response->withHeader('Location','/')->withStatus(302);
});
$app->get('/edit/{id}',function(ServerRequestInterface $request, ResponseInterface $response,$args) use ($twig,$studentmodel){
    $body=$twig->render('edit.twig',[
        'profile'=>$studentmodel->getStudentById($args['id'])
    ]);
    $response->getBody()->write($body);
    return $response;
});
$app->post('/edit-post/{id}',function (ServerRequestInterface $request, ResponseInterface $response,$args) use($auth,$session,$studentmodel){
    $params=(array) $request->getParsedBody();
    try {
        // $auth->signUp($params);
      
        $studentmodel->updateStudent($args['id'],$params['name'],$params['surname'],$params['password'],$params['sex'],$params['groupnum'],$params['email'],$params['egescore']);

    } catch (Exception $e) {
        $session->setData('message',$e->getMessage());
        return $response->withHeader('Location','/signup')->withStatus(302);
    }
   
    return $response->withHeader('Location','/signin')->withStatus(302);
});
$app->run();
 
