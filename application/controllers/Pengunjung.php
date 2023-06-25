<?php
defined('BASEPATH') or exit('no direct script access allowed');

class Pengunjung extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if ($this->session->userdata('email')) redirect('user/home');
        $data['judul'] = "Home";
        $this->load->view('header', $data);
        $this->load->view('home', $data);
        $this->load->view('footer', $data);
    }

    public function pet()
    {
        if ($this->session->userdata('email')) redirect('user/pet');
        $data['judul'] = "Daftar Hewan";
        $this->load->view('header', $data);
        $this->load->view('pet/pet', $data);
        $this->load->view('footer', $data);
    }

    public function produk()
    {
        if ($this->session->userdata('email')) redirect('user/produk');
        $data['judul'] = "Kategori Produk";
        $data['produk'] = $this->ModelProduk->getProduk()->result();
        $this->load->view('header', $data);
        $this->load->view('produk/produk', $data);
        $this->load->view('footer', $data);
    }

    public function galeri()
    {
        if ($this->session->userdata('email')) redirect('user/galeri');
        $data['judul'] = "Galeri Foto";
        $this->load->view('header', $data);
        $this->load->view('tentangkami/galeri', $data);
        $this->load->view('footer', $data);
    }

    public function team()
    {
        if ($this->session->userdata('email')) redirect('user/team');
        $data['judul'] = "Team Kami";
        $this->load->view('header', $data);
        $this->load->view('tentangkami/tim', $data);
        $this->load->view('footer', $data);
    }

    public function kontak()
    {
        if ($this->session->userdata('email')) redirect('user/kontak');
        $data['judul'] = "Hubungi Kami";
        $this->load->view('header', $data);
        $this->load->view('kontak', $data);
        $this->load->view('footer', $data);
    }
}
