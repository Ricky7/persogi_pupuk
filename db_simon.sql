-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Oct 14, 2017 at 10:34 AM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_simon`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(100) NOT NULL,
  `peran` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `nama`, `username`, `password`, `peran`) VALUES
(1, 'Guntur', 'admin', '$2y$10$8RIrRt2H314TchR16O2cYO.avCEaUmaOdcP6XjO/IHosmeH.K9HVG', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id_cart` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `id_customer` int(11) NOT NULL,
  `jumlah` int(10) NOT NULL,
  `tharga` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id_customer` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(100) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `kuota` int(5) NOT NULL,
  `status` enum('Unverified','Verified') NOT NULL,
  `tgl_daftar` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id_customer`, `nama`, `username`, `password`, `no_hp`, `alamat`, `kuota`, `status`, `tgl_daftar`) VALUES
(1, 'Simon', 'simon123', '$2y$10$wJVEUxky47YVkrSFbBz2SeHnYZAab6XTfS96CDEStsGDNGQjaKDka', '085674659987', 'Jl. William Iskandar', 200, 'Verified', '2017-09-08 00:25:17'),
(3, 'Masran', 'masran123', '$2y$10$SMFWMmFEA8BpXn5ZPaMQX.L6G0O1FCcbUDiNDzQWIPFm7dBZmCjzi', '', '', 200, 'Verified', '2017-09-08 13:07:17');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(30) NOT NULL,
  `desk_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`, `desk_kategori`) VALUES
(1, 'Pupuk Organik', 'Deskripsi Pupuk Organik...'),
(2, 'Pupuk Non Organik', 'Deskripsi Pupuk Non Organik...'),
(3, 'Pupuk Hayati', 'Deskripsi Pupuk Hayati...');

-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

CREATE TABLE `order_detail` (
  `id_detail` int(11) NOT NULL,
  `id_order` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `jumlah` int(10) NOT NULL,
  `harga` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_detail`
--

INSERT INTO `order_detail` (`id_detail`, `id_order`, `id_produk`, `jumlah`, `harga`) VALUES
(1, 8, 3, 10, 1650000),
(5, 10, 3, 10, 1650000),
(6, 10, 7, 70, 9450000);

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(11) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `jenis` enum('Subsidi','Non Subsidi') NOT NULL,
  `nama_produk` varchar(30) NOT NULL,
  `desk_produk` varchar(100) NOT NULL,
  `harga` int(15) NOT NULL,
  `stok` int(20) NOT NULL,
  `gambar` varchar(50) NOT NULL,
  `tgl_update` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `id_kategori`, `jenis`, `nama_produk`, `desk_produk`, `harga`, `stok`, `gambar`, `tgl_update`) VALUES
(2, 2, 'Subsidi', 'Pupuk Phonska', 'Deskripsi Pupuk Phonska..', 150000, 75, '427874.png', '2017-09-07 00:51:08'),
(3, 1, 'Subsidi', 'Pupuk Phonska Plus', 'Deskripsi Pupuk Phonska Plus...', 165000, 48, '923494.jpg', '2017-09-07 00:51:55'),
(4, 3, 'Subsidi', 'Pupuk DAP', 'Deskripsi Pupuk DAP', 120000, 50, '108916.png', '2017-09-07 00:52:23'),
(5, 1, 'Subsidi', 'Pupuk Cap Kuda', 'Pupuk Cap Kuda', 125000, 50, '226220.jpg', '2017-09-07 00:56:41'),
(6, 2, 'Non Subsidi', 'Pupuk SP 36', 'Deskripsi Pupuk SP 36', 100000, 90, '753433.png', '2017-09-07 00:53:24'),
(7, 1, 'Non Subsidi', 'Pupuk Petro Cas', 'Dskripsi Pupuk Petro Cas', 135000, 50, '856888.jpg', '2017-09-07 00:54:08'),
(8, 3, 'Subsidi', 'Pupuk ZA', 'Deskripsi Pupuk ZA', 150000, 80, '940909.png', '2017-09-07 00:54:58'),
(9, 1, 'Non Subsidi', 'Pupuk Cap Kuda', 'Deskripsi Pupuk Cap Kuda', 140000, 70, '815378.jpg', '2017-09-07 00:56:22');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order`
--

CREATE TABLE `tbl_order` (
  `id_order` int(11) NOT NULL,
  `id_customer` int(11) NOT NULL,
  `id_admin` int(11) NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `tgl_order` datetime NOT NULL,
  `desk_order` varchar(150) NOT NULL,
  `jarak` int(10) NOT NULL,
  `ongkos` int(20) NOT NULL,
  `status_order` varchar(15) NOT NULL,
  `jenis_bank` varchar(12) NOT NULL,
  `no_rek` varchar(25) NOT NULL,
  `nama_rek` varchar(25) NOT NULL,
  `nilai_transfer` int(15) NOT NULL,
  `ket_transfer` varchar(100) NOT NULL,
  `bukti_bayar` varchar(15) NOT NULL,
  `tgl_terkirim` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_order`
--

INSERT INTO `tbl_order` (`id_order`, `id_customer`, `id_admin`, `no_hp`, `alamat`, `tgl_order`, `desk_order`, `jarak`, `ongkos`, `status_order`, `jenis_bank`, `no_rek`, `nama_rek`, `nilai_transfer`, `ket_transfer`, `bukti_bayar`, `tgl_terkirim`) VALUES
(8, 1, 1, '085674659987', 'Jl. Pancing, Sumatera Utara, Indonesia', '2017-09-08 21:56:52', '', 13, 650000, 'Selesai', 'BNI', '7834587345', 'Simon', 3000000, 'OK', '887864.jpg', '2017-09-09 03:27:29'),
(10, 1, 1, '085674659987', 'Medan Marelan, Medan City, North Sumatra, Indonesia', '2017-09-09 00:55:29', '', 20, 1000000, 'Selesai', 'BRI', '3466536465', 'Simon', 12100000, 'Banyak Coi', '972031.png', '2017-09-09 03:42:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id_cart`),
  ADD KEY `id_produk` (`id_produk`),
  ADD KEY `id_customer` (`id_customer`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id_customer`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_order` (`id_order`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indexes for table `tbl_order`
--
ALTER TABLE `tbl_order`
  ADD PRIMARY KEY (`id_order`),
  ADD KEY `id_customer` (`id_customer`),
  ADD KEY `id_admin` (`id_admin`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id_cart` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id_customer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `order_detail`
--
ALTER TABLE `order_detail`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `tbl_order`
--
ALTER TABLE `tbl_order`
  MODIFY `id_order` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
