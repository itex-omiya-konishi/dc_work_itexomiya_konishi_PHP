
--
-- テーブルの構造 `pcategory`
--

CREATE TABLE `pcategory` (
  `pcategory_id` int(11) NOT NULL,
  `pcategory_name` varchar(100) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- テーブルのデータのダンプ `pcategory`
--
INSERT INTO `pcategory` (`pcategory_id`, `pcategory_name`) VALUES
(1, '風景'),
(2, '人物'),
(3, '動物');

-- --------------------------------------------------------

--
-- テーブルの構造 `photo`
--

CREATE TABLE `photo` (
  `image_id` INT(11) NOT NULL,
  `title` VARCHAR(100) CHARACTER SET utf8 DEFAULT NULL,
  `file_name` VARCHAR(100) CHARACTER SET utf8 DEFAULT NULL,
  `public_flg` INT(11) DEFAULT NULL,
  `create_date` DATE DEFAULT NULL,
  `update_date` DATE DEFAULT NULL,
  PRIMARY KEY (`image_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `photo`
--

INSERT INTO `photo` (`image_id`, `file_name`, `title`, `public_flg`, `create_date`, `update_date`) VALUES
(1, 'スクリーンショット 2025-08-20 112506.png', '宇宙飛行士と月', 1, '2025-09-11', '2025-09-11'),
(2, 'スクリーンショット 2025-08-20 112506.png', '朝日と石', 1, '2025-09-11', '2025-09-11'),
(3, 'スクリーンショット 2025-08-20 112506.png', '鏡のような湖', 0, '2025-09-11', '2025-09-11'),
(4, 'スクリーンショット 2025-08-20 112506.png', 'ハートの風船', 1, '2025-09-11', '2025-09-11');


--
-- Indexes for table `pcategory`
--
ALTER TABLE `pcategory`
  ADD PRIMARY KEY (`pcategory_id`);

--
-- Indexes for table `photo`
--

