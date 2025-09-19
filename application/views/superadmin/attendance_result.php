<!DOCTYPE html>
<html>
<head>
    <title>Attendance Result</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f7f9fc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: Arial, sans-serif;
        }
        .card {
            max-width: 500px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .card-body {
            text-align: center;
            padding: 30px;
        }
        .emoji {
            font-size: 50px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-body">
            <div class="emoji">
                <?= ($type === "success" ? "🎉" : "⚠️") ?>
            </div>
            <div class="alert alert-<?= $type ?>" role="alert" style="font-size:18px;">
                <?= $message ?>
            </div>

            <?php if ($student): ?>
                <p><b>Student:</b> <?= $student->name ?></p>
                <p><b>Today:</b> <?= date('d M Y') ?></p>
            <?php endif; ?>

            <!-- <a href="<?= base_url() ?>" class="btn btn-primary mt-3">Go to Home</a> -->
        </div>
    </div>
</body>
</html>
