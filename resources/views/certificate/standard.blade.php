<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Helvetica', sans-serif; text-align: center; border: 20px solid #4f46e5; padding: 50px; color: #1e1b4b; }
        .header { text-transform: uppercase; letter-spacing: 5px; color: #6366f1; font-size: 20px; margin-bottom: 20px; }
        .main-title { font-size: 50px; font-weight: 900; margin-bottom: 30px; }
        .name { font-size: 35px; font-style: italic; border-bottom: 2px solid #e5e7eb; display: inline-block; padding: 0 40px; margin: 30px 0; }
        .course-title { font-size: 25px; font-weight: bold; color: #4f46e5; }
        .footer { margin-top: 50px; font-size: 12px; color: #9ca3af; line-height: 1.6; }
    </style>
</head>
<body>
    <div class="header">Certificate of Completion</div>
    <div class="main-title">YamLMS Certified</div>
    
    <p>This is to certify that</p>
    <div class="name">{{ $name }}</div>
    
    <p>has successfully completed the training requirements for</p>
    <div class="course-title">{{ $course }}</div>
    
    <div class="footer">
        Issued on {{ $date }} <br>
        <strong>Verification ID: {{ $certificate_id }}</strong> <br>
        This document is a formal record of professional development.
    </div>
</body>
</html>
