<?php

	class Plugin extends DashPlugin{
		public function getInformation(){
			return array("name" => "Disk Usage");
		}

		public function generateView(){
			$form = new DashForm();

			$form->setTitle("Disk Usage");
			$form->center(true);
			$form->addParagraph("The Disk Usage plugin can obtain information about disk usage on the system.");

			$disk_size = number_format(disk_total_space($_SERVER['DOCUMENT_ROOT']) / 1024 / 1024 / 1024, 1);
			$disk_used = number_format((disk_total_space($_SERVER['DOCUMENT_ROOT']) - disk_free_space($_SERVER['DOCUMENT_ROOT'])) / 1024 / 1024 / 1024, 1);

			$percent = ($disk_used / $disk_size) * 100;
			$form->addHTML('<div style="position:relative;padding-top:20px;">');
			$form->addHTML('<div style="position:absolute;left:0;top:0;font-weight:bold;">Used: '.$disk_used.'GB'.'</div>');
			$form->addHTML('<div style="position:absolute;right:0;top:0;font-weight:bold;">Size: '.$disk_size.'GB'.'</div>');
			$form->addProgressBar($percent, number_format($percent, 2).'% full', true);
			$form->addHTML('</div>');

			return $form->generate();
		}

		public function performAction(){
			return false;
		}

		public function showOnMenu(){
			return true;
		}

		public function pluginMenuIcon(){
			return 'disk.png';
		}

		public function pluginDarkMenuIcon(){
			return 'disk.png';
		}

		public function getMenuString(){
			return "Disk Usage";
		}

		public function requiresLogin(){
			return true;
		}

		public function requiresEditorRights(){
			return false;
		}

		public function requiresAdministratorRights(){
			return true;
		}

	}

?>
