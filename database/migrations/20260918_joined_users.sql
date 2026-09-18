-- Valetax / CodeIgniter 4
-- Migrasi aman untuk fitur Anggota Bergabung.
-- Tidak menghapus kolom/baris dan dapat dijalankan ulang.

-- Sinkronkan timestamp selesai yang masih kosong pada data lama yang sudah
-- mencapai progress terakhir.
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
-- Proses bot tetap boleh melakukan INSERT/UPDATE seperti sebelumnya. Query
-- aplikasi memakai current_step >= 6 sebagai sumber kebenaran status selesai.
