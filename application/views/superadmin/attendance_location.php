<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Attendance</title>
    <style>
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            color: #333;
        }
        .card {
            background: #fff;
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0px 8px 25px rgba(0, 0, 0, 0.15);
            text-align: center;
            max-width: 400px;
            width: 100%;
            animation: fadeIn 0.8s ease-in-out;
        }
        .card h2 {
            margin-bottom: 10px;
            color: #0077ff;
        }
        .card p {
            font-size: 15px;
            margin-bottom: 20px;
            color: #555;
        }
        .btn {
            display: inline-block;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            border: none;
            background: #0077ff;
            color: #fff;
            cursor: pointer;
            transition: 0.3s;
        }
        .btn:hover {
            background: #0056cc;
        }
        .btn:active {
            transform: scale(0.97);
        }
        .icon {
            font-size: 40px;
            margin-bottom: 15px;
            color: #00c853;
            animation: pulse 1.5s infinite;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes pulse {
            0% { transform: scale(1); opacity: 0.9; }
            50% { transform: scale(1.1); opacity: 1; }
            100% { transform: scale(1); opacity: 0.9; }
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">📍</div>
        <h2>Mark Your Attendance</h2>
        <p>Please allow location access so we can confirm your presence near the center.</p>
        <button class="btn" onclick="getLocation()">Mark Attendance</button>
    </div>

    <script>
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(sendAttendance, showError);
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }

    function sendAttendance(position) {
        let lat = position.coords.latitude;
        let lng = position.coords.longitude;

        fetch("<?= base_url('Admission/mark_process/'.$token) ?>", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "latitude=" + lat + "&longitude=" + lng
        })
        .then(response => response.text())
        .then(data => {
            document.body.innerHTML = data; // Show attendance result
        });
    }

    function showError(error) {
        switch(error.code) {
            case error.PERMISSION_DENIED:
                alert("⚠️ You denied location access. Attendance cannot be marked.");
                break;
            case error.POSITION_UNAVAILABLE:
                alert("❌ Location info unavailable.");
                break;
            case error.TIMEOUT:
                alert("⏳ Request to get location timed out.");
                break;
            default:
                alert("An unknown error occurred.");
                break;
        }
    }
    </script>
</body>
</html>
