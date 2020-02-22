<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */

 /**
 * Gère l'authentification et les redirections lors de la connexion.
 * Les fields de 'Form' sont liés à ceux de la page Template/Element/header.ctp.
 *
 * On a utilisé des tables personnalisées plutôt que le modèle Users proposé par CakePhp donc de nombreuses modifications sont faites ;
 * pour préciser qu'on utilise notre propre modèle, il ne faut pas oublier de préciser userModel => Utilisateur.
 *
 * Auteur : POP Diana, ROSSI Djessy
 *
 */
class AppController extends Controller
{


    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);

        $this->loadComponent('Flash');
        $this->loadComponent('Auth', [
          'authenticate' => [
                'Form' =>
                [
                      'fields'=> ['username' => 'email',  'password' => 'mdp'],
                      'userModel' => 'Utilisateur',
                ]
            ],
          'loginAction' => [
            'controller' => 'Utilisateur',
            'action' => 'login'
          ],
            'loginRedirect' => [
                'controller' => 'Accueil',
                'action' => 'index'
            ],
            'logoutRedirect' => [
                'controller' => 'Pages',
                'action' => 'display',
                'home'
            ],
            'authError' => 'Vous devez vous connecter pour accéder à cette page.',
            ['controller'=>'Pages', 'action' => 'display','home'],

          'unauthorizedRedirect'=>[$this->referer()]]

      );


        /*
         * Enable the following component for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
    }

  /**
  * Pris de la doc officielle.
  *
  * Auteur : POP Diana
  */
    public function beforeFilter(Event $event)
    {
        $this->Auth->allow([ 'view', 'display']);

    }

    /**
    * Booléen permettant d'afficher le header différemment selon si l'utilisateur est connecté ou non.
    *
    * Auteur : ROSSI Djessy
    */
    public function beforeRender(Event $event){
      if ($this->request->getSession()->read('Auth.User')){
        $this->set('loggedIn', true);
      }else{
        $this->set('loggedIn', false);
      }
    }
}
