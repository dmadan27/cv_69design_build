<?php
	interface CrudInterface{
		private function list();

		private function add();

		public function actionAdd();

		private function edit($id);

		public function actionEdit();

		private function detail($id);

		public function delete($id);

		public function export();
	}