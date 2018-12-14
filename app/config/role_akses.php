<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	 * Masih tahap pengembangan
	 * Kedepannya akan ada access right berdasarkan role atau level user
	 * Sementara masih statis, update selanjutnya akan dinamis menggunakan db
	 */

	// action => array()
	// action => list, view, action-view, add, edit, delete, update-status, reset

	define('BASE_ROLE', array(
		'owner' => array(
			'proyek' => array(
				'action' => array('list', 'view'),
				'url' => ''
			),
		),
		'kas besar' => array(

		),
		'kas kecil' => array(

		),
	));