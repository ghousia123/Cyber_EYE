<?php
// 1. Database Connection
$servername = "127.0.0.1:3307"; 
$username = "root"; 
$password = ""; 
$dbname = "cyber_eye_db";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
} catch (mysqli_sql_exception $e) {
    die("<div style='color:white; background:#0b0b0b; padding:20px; text-align:center;'><h2>Connection Failed</h2></div>");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);

    $score = 0;
    $ans1 = $_POST['q1'] ?? '';
    $ans2 = $_POST['q2'] ?? '';
    $ans3 = $_POST['q3'] ?? '';

    $answer_key = [
        "Fundamentals" => ["Confidentiality", "Human Psychology", "Biometrics"],
        "Network Security" => ["Firewall", "443", "Virtual Private Network"],
        "ISO Standards" => ["ISO 27001", "Code of Practice", "International Organization for Standardization"]
    ];

    if ($ans1 == $answer_key[$course][0]) $score++;
    if ($ans2 == $answer_key[$course][1]) $score++;
    if ($ans3 == $answer_key[$course][2]) $score++;

    if ($score == 3) {
        $sql = "INSERT INTO student_certificates (student_name, course_name) VALUES ('$name', '$course')";

        if ($conn->query($sql) === TRUE) {
            $cert_id = $conn->insert_id;
            
            echo "
            <html>
            <head>
                <title>Cyber Eye Certificate - $name</title>
                <style>
                    :root {
                        --main-bg: #0f172a;
                        --card-bg: #ffffff;
                        --primary: #0d9488;
                        --secondary: #b45309;
                        --text-dark: #1e293b;
                        --text-light: #64748b;
                    }
                    body { 
                        background: var(--main-bg); 
                        display: flex; 
                        flex-direction: column;
                        align-items: center; 
                        justify-content: center; 
                        min-height: 100vh; 
                        margin: 0; 
                        font-family: 'Times New Roman', serif; 
                    }
                    .cert-card {
                        width: 700px; 
                        padding: 40px; 
                        background: var(--card-bg); 
                        text-align: center;
                        border: 15px solid #f8fafc;
                        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
                        position: relative;
                        outline: 2px solid var(--primary);
                        outline-offset: -12px;
                    }
                    .header {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        margin-bottom: 20px;
                        border-bottom: 2px solid #e2e8f0;
                        padding-bottom: 15px;
                    }
                    .ssuet-logo { width: 80px; height: 80px; object-fit: contain; }
                    .uni-info { text-align: right; }
                    .uni-info h2 { margin: 0; color: var(--text-dark); font-size: 18px; text-transform: uppercase; }
                    .uni-info p { margin: 0; color: var(--primary); font-weight: bold; font-size: 13px; }
                    
                    .cert-title { margin: 20px 0; }
                    .cert-title h1 { font-size: 40px; margin: 5px 0; color: var(--text-dark); letter-spacing: 4px; }
                    .cert-title p { color: var(--secondary); font-weight: bold; letter-spacing: 2px; text-transform: uppercase; margin: 0; font-size: 14px; }
                    
                    .student-name { font-size: 36px; color: var(--primary); margin: 15px 0; font-style: italic; font-weight: bold; text-decoration: underline; }
                    
                    /* Gap barhane ke liye yahan changing ki hai */
                    .course-info { font-size: 16px; color: var(--text-light); margin-bottom: 35px; }
                    .course-info strong { color: var(--text-dark); display: block; font-size: 22px; margin-top: 8px; }
                    
                    .cert-id {
                        display: inline-block;
                        background: #f1f5f9;
                        padding: 4px 12px;
                        font-family: monospace;
                        font-size: 12px;
                        color: var(--text-light);
                        border-radius: 4px;
                        margin-bottom: 20px; /* Neeche space k liye */
                    }

                    .footer { display: flex; justify-content: space-between; align-items: flex-end; margin-top: 20px; }
                    .sig-block { width: 160px; }
                    .sig-name { border-top: 1px solid var(--text-dark); padding-top: 5px; font-size: 13px; font-weight: bold; color: var(--text-dark); }
                    .sig-title { font-size: 11px; color: var(--text-light); }
                    .cursive-sig { font-family: 'Brush Script MT', cursive; font-size: 26px; color: #1e293b; margin-bottom: -5px; }

                    /* Button Container mein gap add kiya */
                    .btn-container { margin-top: 40px; margin-bottom: 20px; }
                    .print-btn { 
                        background: var(--primary); 
                        color: white; border: none; 
                        padding: 12px 30px; cursor: pointer;
                        font-weight: bold; border-radius: 4px;
                        box-shadow: 0 4px 15px rgba(13, 148, 136, 0.3);
                        text-transform: uppercase;
                        font-size: 14px;
                    }
                    @media print { .btn-container { display: none; } }
                </style>
            </head>
            <body>
                <div class='cert-card'>
                    <div class='header'>
                        <img src='ssuet.jpeg' class='ssuet-logo'>
                        <div class='uni-info'>
                            <h2>Sir Syed University</h2>
                            <p>Engineering & Technology</p>
                        </div>
                    </div>

                    <div class='cert-title'>
                        <p>Cyber Eye Operations</p>
                        <h1>CERTIFICATE</h1>
                        <span style='color:var(--text-light); font-size: 13px;'>OF COMPLETION</span>
                    </div>

                    <div class='course-info'>
                        This is to certify that the student
                        <div class='student-name'>$name</div>
                        has successfully cleared the professional module of
                        <strong>$course</strong>
                    </div>

                    <div class='cert-id'>
                        ID: CE-SSUET-" . str_pad($cert_id, 5, '0', STR_PAD_LEFT) . "
                    </div>

                    <div class='footer'>
                        <div class='sig-block'>
                            <div class='cursive-sig'>Faizan Saleem</div>
                            <div class='sig-name'>Engr.Faizan Saleem</div>
                            <div class='sig-title'>Coordinator Cyber Eye</div>
                        </div>
                        <div style='text-align:center; font-size: 11px;'>
                            <strong style='color:var(--primary)'>DATE</strong><br>
                            " . date("d-M-Y") . "
                        </div>
                        <div class='sig-block'>
                            <div class='cursive-sig'>Dr. Amir</div>
                            <div class='sig-name'>Dr. Amir</div>
                            <div class='sig-title'>Dean of SSUET</div>
                        </div>
                    </div>
                </div>

                <div class='btn-container'>
                    <button class='print-btn' onclick='window.print()'>Print Digital Certificate</button>
                </div>
            </body>
            </html>";
        }
    } else {
        echo "<script>alert('Failed!'); window.history.back();</script>";
    }
}
$conn->close();
?>