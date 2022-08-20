<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('Admin_model');
		if($this->session->userdata('login_status') != 'login') {
			redirect('login');
		}
	}

	public function index() {
		$data['title'] = 'Halaman Admin';
		$data['totalbuku'] = $this->db->get('tb_buku')->num_rows();
		$data['totaluser'] = $this->db->get('tb_user')->num_rows();
		$data['totalpeminjam'] = $this->Admin_model->total_peminjam();
		$data['datapeminjam'] = $this->Admin_model->data_peminjam();
		$data['datauser'] = $this->Admin_model->data_user();
		$this->load->view('tema/header', $data);
		$this->load->view('admin/index', $data);
		$this->load->view('tema/footer');
	}

	// book start here

	public function buku() {
		$data['title'] = 'Data Buku Perpustakaan';
		$data['databuku'] = $this->Admin_model->data_buku();
		$this->load->view('tema/header', $data);
		$this->load->view('admin/buku', $data);
		$this->load->view('tema/footer');
	}

	public function add_buku() {
		$data['title'] = 'Tambah Data Buku';
		$this->form_validation->set_rules('judul', 'judul', 'required|min_length[3]', [
					'required'	=>	'Kolom judul buku tidak boleh kosong',
					'min_length'=>	'Nama buku minimal 3 karakter']);
		$this->form_validation->set_rules('author', 'author', 'required|min_length[3]', [
					'required'	=>	'Kolom penulis tidak boleh kosong',
					'min_length'=>	'Nama penulis minimal 3 karakter']);
		$this->form_validation->set_rules('jml_hal', 'jml_hal', 'required|numeric|min_length[2]|max_length[4]', [
					'required'	=>	'Kolom jumlah halaman tidak boleh kosong',
					'min_length'=>	'jumlah halaman minimal 2 angka',
					'numeric'	=>	'Jumlah halaman harus berupa angka',
					'max_length'=>	'jumlah halaman maksimal 4 angka']);
		$this->form_validation->set_rules('kat_buku', 'kat_buku', 'required', [
					'required'	=>	'Kolom kategori buku tidak boleh kosong']);
		$this->form_validation->set_rules('deskripsi', 'deskripsi', 'required|min_length[5]', [
					'required'	=>	'Kolom deskripsi buku tidak boleh kosong',
					'min_length'=>	'Deskripsi buku minimal 5 karakter']);
		if($this->form_validation->run() == FALSE) {
			$this->load->view('tema/header', $data);
			$this->load->view('admin/add_buku', $data);
			$this->load->view('tema/footer');
		}else {
			$this->Admin_model->simpan_buku();
			$this->session->set_flashdata('flash', 'Data buku berhasil ditambahkan');
			redirect('admin/buku');
		}
	}

	public function edit_buku($id) {
		$data['title'] = 'Edit Data Buku';
		$data['bukuid'] = $this->Admin_model->bukubyid($id);
		$this->form_validation->set_rules('judul', 'judul', 'required|min_length[3]', [
					'required'	=>	'Kolom judul buku tidak boleh kosong',
					'min_length'=>	'Nama buku minimal 3 karakter']);
		$this->form_validation->set_rules('author', 'author', 'required|min_length[3]', [
					'required'	=>	'Kolom penulis tidak boleh kosong',
					'min_length'=>	'Nama penulis minimal 3 karakter']);
		$this->form_validation->set_rules('jml_hal', 'jml_hal', 'required|numeric|min_length[2]|max_length[4]', [
					'required'	=>	'Kolom jumlah halaman tidak boleh kosong',
					'min_length'=>	'jumlah halaman minimal 2 angka',
					'numeric'	=>	'Jumlah halaman harus berupa angka',
					'max_length'=>	'jumlah halaman maksimal 4 angka']);
		$this->form_validation->set_rules('kat_buku', 'kat_buku', 'required', [
					'required'	=>	'Kolom kategori buku tidak boleh kosong']);
		$this->form_validation->set_rules('deskripsi', 'deskripsi', 'required|min_length[5]', [
					'required'	=>	'Kolom deskripsi buku tidak boleh kosong',
					'min_length'=>	'Deskripsi buku minimal 5 karakter']);
		if($this->form_validation->run() == FALSE) {
			$this->load->view('tema/header', $data);
			$this->load->view('admin/edit_buku', $data);
			$this->load->view('tema/footer');
		}else {
			$this->Admin_model->ubah_buku();
			$this->session->set_flashdata('flash', 'Data buku berhasil diedit');
			redirect('admin/buku');
		}
	}

	public function hapus_buku($id) {
		$this->Admin_model->del_buku($id);
		$this->session->set_flashdata('flash', 'Data buku berhasil dihapus');
		redirect('admin/buku');
	}

	// data user start here

	public function data_member() {
		$data['title'] = 'Data Member';
		$data['datamember'] = $this->Admin_model->data_user_all();
		$this->load->view('tema/header', $data);
		$this->load->view('admin/data_member', $data);
		$this->load->view('tema/footer');
	}

	// data peminjam

	public function data_peminjam() {
		$data['title'] = 'Data Peminjaman Buku';
		$data['datapinjam'] = $this->Admin_model->data_pinjam_all();
		$this->load->view('tema/header', $data);
		$this->load->view('admin/data_peminjaman', $data);
		$this->load->view('tema/footer');
	}

	public function kembalikan() {
		$id_book = $this->uri->segment(3);
		if($id_book == '') {
			redirect('admin/data_peminjam');
		}
		$ceksisa = $this->db->get_where('tb_buku',['id_buku' => $id_book])->row_array();
		$sisa = $ceksisa['jumlah_buku']+1;

		$this->db->set('jumlah_buku', $sisa);
		$this->db->where('id_buku', $id_book);
		$this->db->update('tb_buku');
		$this->db->delete('tb_pinjaman', ['id_user_pinjaman' => $this->uri->segment(5), 'id_buku_pinjaman' => $id_book]);
		$data = array (
			'id_buku_pengembalian'		=>   $id_book,
			'id_user_pengembalian'		=>   $this->uri->segment(5)
		);
	
		$this->db->insert('tb_pengembalian', $data);
		$this->session->set_flashdata('flash', 'Buku berhasil dikembalikan');
		redirect('admin/data_peminjam');
	}

	public function data_pengembalian() {
		$data['title'] = 'Data Pengembalian Buku';
		$data['datakembali'] = $this->Admin_model->data_pengembalian_buku();
		$this->load->view('tema/header', $data);
		$this->load->view('admin/data_pengembalian', $data);
		$this->load->view('tema/footer');
	}

	public function buku_sumbangan() {
		$data['title'] = 'Data Buku Sumbangan';
		$data['buku'] = $this->Admin_model->data_buku_sumbangan();
		$this->load->view('tema/header', $data);
		$this->load->view('admin/data_sumbangan_buku', $data);
		$this->load->view('tema/footer');
	}

	public function konfirmasi_buku($id) {
		$this->db->set('subu_status', 'Dikonfirmasi');
		$this->db->where('subu_id', $id);
		$this->db->update('tb_sumbang_buku');
		redirect('admin/buku_sumbangan');
	}

	public function terima_buku($id) {
		$this->db->set('subu_status', 'Diterima');
		$this->db->where('subu_id', $id);
		$this->db->update('tb_sumbang_buku');
		redirect('admin/buku_sumbangan');
	}
}