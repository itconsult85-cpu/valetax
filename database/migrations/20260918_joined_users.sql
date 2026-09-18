-- Valetax / CodeIgniter 4
-- Migrasi aman untuk fitur Anggota Bergabung.
-- Tidak menghapus kolom/baris dan dapat dijalankan ulang.

-- Sinkronkan timestamp selesai yang masih kosong pada data lama yang sudah
-- mencapai progress terakhir versi alur baru. Data historis yang sudah
-- memiliki completed_at tetap dipertahankan apa adanya.
UPDATE `user_progress`
SET `completed_at` = COALESCE(`completed_at`, `last_active`, `started_at`, CURRENT_TIMESTAMP)
WHERE `current_step` >= 6
  AND `completed_at` IS NULL;

-- Pastikan penyelesaian berikutnya selalu menyimpan completed_at.
DROP TRIGGER IF EXISTS `trg_user_progress_completed_at_insert`;
DROP TRIGGER IF EXISTS `trg_user_progress_completed_at`;

DELIMITER $$
CREATE TRIGGER `trg_user_progress_completed_at_insert`
BEFORE INSERT ON `user_progress`
FOR EACH ROW
BEGIN
    IF NEW.`current_step` >= 6 AND NEW.`completed_at` IS NULL THEN
        SET NEW.`completed_at` = CURRENT_TIMESTAMP;
    END IF;
END$$

CREATE TRIGGER `trg_user_progress_completed_at`
BEFORE UPDATE ON `user_progress`
FOR EACH ROW
BEGIN
    IF NEW.`current_step` >= 6
       AND OLD.`current_step` < 6
       AND NEW.`completed_at` IS NULL THEN
        SET NEW.`completed_at` = CURRENT_TIMESTAMP;
    END IF;
END$$
DELIMITER ;

-- Catatan integrasi bot:
-- Pada data historis, completed_at + screenshots_sent >= 1 adalah indikator
-- selesai. Untuk alur bot baru, current_step = 6 juga dipakai sebagai status
-- final dan trigger di atas membantu mengisi completed_at secara otomatis.
