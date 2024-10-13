
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_warung`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_masakan`
--
CREATE TABLE `tb_masakan` (
  `id_masakan` int NOT NULL,
  `nama_masakan` varchar(100) NOT NULL,
  `gambar_masakan` varchar(255) DEFAULT NULL,
  `harga` decimal(10,2) NOT NULL,
  `status_masakan` enum('tersedia','tidak tersedia') NOT NULL,
  `stok` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `tb_masakan` (`id_masakan`, `nama_masakan`, `gambar_masakan`, `harga`, `status_masakan`, `stok`) VALUES
(1, 'Nasi Goreng', 'nasi_goreng.jpg', '15000.00', 'tersedia', 7),
(2, 'Mie Goreng', 'mie_goreng.jpg', '12000.00', 'tersedia', 8),
(3, 'Ayam Penyet', 'ayam_penyet.jpg', '20000.00', 'tersedia', 10),
(4, 'Sate Ayam', 'sate_ayam.jpg', '25000.00', 'tersedia', 10),
(5, 'Es Teh', 'es_teh.jpg', '5000.00', 'tersedia', 8),
(6, 'Es Jeruk', 'es_jeruk.jpg', '7000.00', 'tersedia', 8),
(7, 'Kepala Ikan Bakar', 'kepala_ikan_bakar.jpg', '25000.00', 'tersedia', 10),

 CREATE TABLE `tb_order` (
  `id_order` int NOT NULL,
  `id_user` int NOT NULL,
  `no_meja` varchar(20) NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `waktu_pesan` datetime NOT NULL,
  `status` enum('belum diproses','sudah diproses') DEFAULT 'belum diproses'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `tb_pesan` (
  `id_pesan` int NOT NULL,
  `id_order` int NOT NULL,
  `id_masakan` int NOT NULL,
  `jumlah` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `tb_riwayat_transaksi` (
  `id_transaksi` int NOT NULL,
  `id_order` int NOT NULL,
  `nama_user` varchar(100) NOT NULL,
  `no_meja` varchar(20) NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `pembayaran` decimal(10,2) NOT NULL,
  `kembalian` decimal(10,2) NOT NULL,
  `waktu_transaksi` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `tb_user` (
  `id_user` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_user` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `tb_user` (`id_user`, `username`, `password`, `nama_user`) VALUES
(3, 'admin', 'admin123', 'Admin Warung'),

ALTER TABLE `tb_masakan`
  ADD PRIMARY KEY (`id_masakan`);

ALTER TABLE `tb_order`
  ADD PRIMARY KEY (`id_order`),
  ADD KEY `id_user` (`id_user`);

ALTER TABLE `tb_pesan`
  ADD PRIMARY KEY (`id_pesan`),
  ADD KEY `id_order` (`id_order`),
  ADD KEY `id_masakan` (`id_masakan`);

ALTER TABLE `tb_riwayat_transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_order` (`id_order`);
