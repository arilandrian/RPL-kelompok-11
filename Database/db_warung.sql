CREATE TABLE `tb_menu` (
  `id_menu` int(11) NOT NULL,
  `nama_menu` varchar(150) NOT NULL,
  `harga` varchar(150) NOT NULL,
  `stok` int(11) NOT NULL,
  `gambar_masakan` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `tb_user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(150) NOT NULL,
  `password` varchar(150) NOT NULL,
  `nama_user` varchar(150) NOT NULL,
  `id_level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE tb_stok (
  id_stok INT(11) NOT NULL,
  jumlah INT(11) NOT NULL
  );
CREATE TABLE pesanan (
  id INT(11) NOT NULL,
  jumlah INT(11) NOT NULL,
  status_pesanan ENUM );
CREATE TABLE detail_pesanan (
  id INT(11) NOT NULL,
  no_meja INT(11) NOT NULL,
  waktu_pesanan DATETIME NOT NULL,
  total_harga DECIMAL(10, 2) NOT NULL,
  uang_bayar DECIMAL(10, 2) NOT NULL,
  uang_kembali DECIMAL(10, 2) NOT NULL );


 
