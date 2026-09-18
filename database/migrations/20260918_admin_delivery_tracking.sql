-- Valetax / CodeIgniter 4
-- Tracking pengiriman data user ke admin Telegram.
-- Jalankan satu kali pada database bot_db.
-- Tidak menghapus atau mengubah data lama.

ALTER TABLE `user_progress`
    ADD COLUMN `admin_notified_at` DATETIME NULL DEFAULT NULL AFTER `completed_at`,
    ADD COLUMN `admin_forwarded_at` DATETIME NULL DEFAULT NULL AFTER `admin_notified_at`,
    ADD COLUMN `admin_sent_at` DATETIME NULL DEFAULT NULL AFTER `admin_forwarded_at`,
    ADD COLUMN `admin_message_id` BIGINT NULL DEFAULT NULL AFTER `admin_sent_at`,
    ADD COLUMN `admin_forward_message_id` BIGINT NULL DEFAULT NULL AFTER `admin_message_id`;

ALTER TABLE `user_progress`
    ADD KEY `idx_admin_sent_at` (`admin_sent_at`),
    ADD KEY `idx_admin_delivery` (`completed_at`, `admin_sent_at`);

-- Data lama sengaja tidak di-backfill ke admin_sent_at. Database lama tidak
-- menyimpan bukti bahwa sendMessage/forwardMessage Telegram benar-benar sukses.
-- Dengan demikian menu hanya menampilkan pengiriman yang terverifikasi mulai
-- dari bot versi baru.
