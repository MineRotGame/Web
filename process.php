<?php
// ตรวจสอบว่ามีการส่งข้อมูลผ่านแบบฟอร์ม POST หรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ตรวจสอบว่ามีข้อมูลที่ส่งมาแล้วหรือไม่
    if (isset($_POST['amount']) && isset($_POST['account_number']) && isset($_FILES['qr_code'])) {
        // ดึงข้อมูลจากแบบฟอร์ม
        $amount = $_POST['amount'];
        $account_number = $_POST['account_number'];

        // ตรวจสอบว่าไฟล์ที่อัปโหลดเป็นไฟล์รูปภาพหรือไม่
        $file_type = exif_imagetype($_FILES['qr_code']['tmp_name']);
        if ($file_type === false) {
            echo "Error: กรุณาเลือกรูปภาพที่ถูกต้อง (PNG, JPEG, GIF)";
            exit();
        }

        // ตั้งค่าการอัปโหลดไฟล์
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["qr_code"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        // เช็คขนาดของไฟล์
        if ($_FILES["qr_code"]["size"] > 500000) {
            echo "ขออภัย, ไฟล์ที่อัปโหลดมีขนาดใหญ่เกินไป";
            $uploadOk = 0;
        }

        // ตรวจสอบว่าไฟล์มีอยู่หรือไม่
        if (file_exists($target_file)) {
            echo "ขออภัย, ไฟล์นี้มีอยู่แล้ว";
            $uploadOk = 0;
        }

        // ตรวจสอบรูปแบบไฟล์
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            echo "ขออภัย, เฉพาะไฟล์ JPG, JPEG, PNG & GIF เท่านั้นที่อนุญาต";
            $uploadOk = 0;
        }

        // ตรวจสอบว่า $uploadOk มีค่าเป็น 0 หรือไม่
        if ($uploadOk == 0) {
            echo "ขออภัย, ไฟล์ของคุณไม่ได้อัปโหลดสำเร็จ";
        // ถ้าทุกอย่างถูกต้องให้อัปโหลดไฟล์
        } else {
            if (move_uploaded_file($_FILES["qr_code"]["tmp_name"], $target_file)) {
                echo "ไฟล์ ". htmlspecialchars( basename( $_FILES["qr_code"]["name"])). " ถูกอัปโหลดสำเร็จ";
                // ทำอื่นๆ ที่คุณต้องการ เช่น บันทึกข้อมูลลงฐานข้อมูล
            } else {
                echo "เกิดข้อผิดพลาดในการอัปโหลดไฟล์";
            }
        }
    } else {
        echo "ข้อมูลไม่ครบถ้วน";
    }
}
?>