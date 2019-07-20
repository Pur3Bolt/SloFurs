<?php
class Admin extends Connection{
	public function index(){
		$account=$this->getSessionAcc();
		if($account!=null&&$account->status>=ADMIN){
			header('location: '.URL.'admin/dash');
		}
		elseif($account->status<STAFF){
			header('location: '.URL.'404');
		}
		else{
			header('location: '.URL.'login');
		}
	}
	// Dashboard
	public function dash(){
		$account=$this->getSessionAcc();
		$dash_model=$this->loadSQL('DashboardModel');
		if($account==null){
			header('location: '.URL.'login');
		}
		elseif($account->status>=ADMIN){
			require 'app/sites/global/header.php';
			require 'app/sites/global/alerts.php';
			require 'app/sites/'.THEME.'/admin/dash.php';
			require 'app/sites/global/footer.php';
		}
		else{
			header('location: '.URL.'404');
		}
	}
	// Event managing page
	public function event($action=null){
		$account=$this->getSessionAcc();
		$event_model=$this->loadSQL('EventModel');
		if($account==null){
			header('location: '.URL.'login');
		}
		elseif($account->status>=ADMIN){
			//create new event
			if(isset($_POST['new_event'])){
				$event_model=$this->loadSQL('EventModel');
				$_SESSION['alert']=$event_model->addEvent($_POST, $_FILES['image']);
				header('location: '.URL.'admin/event');
			}
			//edit event with given ID
			elseif(isset($_POST['edit_event'])){
				$event_model=$this->loadSQL('EventModel');
				$_SESSION['alert']=$event_model->editEvent($_GET['id'], $_POST, $_FILES['image']);
				header('location: '.URL.'admin/event?id='.$_GET['id']);
			}
			//delete event photo with given ID
			elseif(isset($_POST['delete_photo'])){
				$event_model=$this->loadSQL('EventModel');
				$_SESSION['alert']=$event_model->deletePhoto($_GET['id']);
				header('location: '.URL.'admin/event?id='.$_GET['id']);
			}
			//edit confirmed users
			elseif(isset($_POST['confirm_attendees'])){
				$_SESSION['alert']=$event_model->editConfirm($_GET['id'], $_POST);
				header('location: '.URL.'admin/event?id='.$_GET['id']);
			}
			else{
				require 'app/sites/global/header.php';
				require 'app/sites/global/alerts.php';
				//go to new event creation page
				if($action=='new'){
					require 'app/sites/'.THEME.'/admin/sidebar.php';
					require 'app/sites/'.THEME.'/admin/newevent.php';
				}
				//go to edit/view event page
				else{
					if(isset($_GET['id'])){
						$event=$event_model->getEvent($_GET['id']);
						$attendees=$event_model->getRegistered($_GET['id']);
						//$rooms=$event_model->getRooms($_GET['id']);
						$fursuits=$event_model->getFursuits($_GET['id']);
						require 'app/sites/'.THEME.'/admin/event_overview.php';
					}
					//list events
					else{
						$cEvents=$event_model->getCEvents(); //current/upcoming
						$pEvents=$event_model->getPEvents(); //past
						require 'app/sites/'.THEME.'/admin/sidebar.php';
						require 'app/sites/'.THEME.'/admin/event.php';
					}
				}
				require 'app/sites/global/footer.php';
			}
		}
		//user without admin privileges goes to 404
		else{
			header('location: '.URL.'404');
		}
	}
}
