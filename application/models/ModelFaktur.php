<?php
defined('BASEPATH') or exit('No direct script access allowed');
class ModelFaktur extends CI_Model
{
    public function saveFaktur($where)
    {
        $this->db->insert('faktur', $where);
        $id_faktur = $this->db->insert_id();

        return $id_faktur;
    }

    public function saveDetailFaktur($id_faktur)
    {
        foreach ($this->cart->contents() as $item) {
            $data = array(
                'id_faktur' => $id_faktur,
                'id_barang' => $item['id'],
                'nama_barang' => $item['name'],
                'jumlah' => $item['qty'],
                'harga' => $item['price'],
            );
            $this->db->insert('detail_faktur', $data);

            $stok = $this->db->select('stok')->where(['id' => $item['id']])->get('produk')->result_array()[0]['stok'];
            $this->db->set('stok', $stok - $item['qty']);
            $this->db->where('id', $item['id']);
            $this->db->update('produk');
        }
    }

    public function tampilData()
    {
        $result = $this->db->get('faktur');
        if ($result->num_rows() > 0) {
            return $result->result();
        } else {
            return false;
        }
    }

    public function ambilIdFaktur($id_faktur)
    {
        $result = $this->db->where('id', $id_faktur)->limit(1)->get('faktur');
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return false;
        }
    }

    public function ambilIdDetFaktur($id_faktur)
    {
        $result = $this->db->where('id_faktur', $id_faktur)->get('detail_faktur');
        if ($result->num_rows() > 0) {
            return $result->result();
        } else {
            return false;
        }
    }

    public function hapusFaktur($where)
    {
        $data = $this->db->select('detail_faktur.id_barang,detail_faktur.jumlah,produk.stok')
            ->join('produk', 'produk.id=detail_faktur.id_barang', 'left')
            ->where('id_faktur', $where)
            ->get('detail_faktur')->result();

        foreach ($data as $d) {
            $this->db->set('stok', $d->stok + $d->jumlah)->where('id', $d->id_barang)->update('produk');
        }

        $this->db->where(['id' => $where])->delete('faktur');
        $this->db->where(['id_faktur' => $where])->delete('detail_faktur');
    }

    public function laporan()
    {
        $month = date('m');
        $year = date('Y');
        $this->db->select('faktur.id,faktur.nama,produk.keterangan,detail_faktur.harga,detail_faktur.jumlah,faktur.tgl_pesan')
            ->from('faktur')
            ->join('detail_faktur', 'detail_faktur.id_faktur=faktur.id', 'left')
            ->join('produk', 'produk.id=detail_faktur.id_barang', 'left')
            ->where("MONTH(faktur.tgl_pesan) = $month AND YEAR(faktur.tgl_pesan) = $year")
            ->order_by('faktur.tgl_pesan', 'ASC');
        return $this->db->get()->result_array();
    }
}
