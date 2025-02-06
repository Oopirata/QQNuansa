<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Email Notification</title>
</head>
<body>
    <h2>{!! $position !!}</h2>
    
    <p>Dear {!! $name !!},</p>
    
    <p>{!! $messages !!}</p>

    @if($interviewSchedule)
        <p>Interview Schedule: {!! $interviewSchedule !!}</p>
    @endif

    <p>Best regards,<br>HR Team</p>
</body>
</html>