<?php

//memulai sesi
    session_start();
    session_destroy();
    
//kembali ke halaman login/utama.
    include'index.php';
