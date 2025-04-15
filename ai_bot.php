<?php
header('Content-Type: application/json'); // JSON হেডার

$api_key = "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";
$user_message = htmlspecialchars($_GET['msg'] ?? 'তুমি কে?', ENT_QUOTES, 'UTF-8');

$keywords = [
    // সব কিওয়ার্ড আগের মতো
    'তুমি কে', 'Programmer Raj', 'Developer Raj', 'AI অ্যাপ বানিয়েছে কে', 'এই অ্যাপের মালিক কে',
    'এই অ্যাপের ডেভেলপার কে', 'রাজ কে', 'programmer raj কে', 'আপনার নাম কী', 'তুমি কি কোডিং জানো',
    'Who is the developer of this app?', 'Who is Raj?', 'Who created this app?', 'developer of the AI app', 'developer name',
    'What is the name of the developer?', 'Who is behind this app creation?', 'creator of this app', 'Who created the software?'
];

$keywords = array_map('strtolower', $keywords);
$keywords = array_unique($keywords);

foreach ($keywords as $keyword) {
    if (stripos(strtolower($user_message), $keyword) !== false) {
        if (stripos($keyword, 'তুমি কে') !== false || stripos($keyword, 'রাজ কে') !== false) {
            echo json_encode([
                "status" => "success",
                "reply" => "আমি রাজ, একজন প্রোগ্রামার ও ডিজিটাল মার্কেটার। ৪ বছরের অভিজ্ঞতা, অ্যান্ড্রয়েড অ্যাপস আর ওয়েবসাইট বানাই। মানুষকে হেল্প করতে ভালোবাসি। ইউটিউব চ্যানেল: The Village Coder।"
            ]);
            exit;
        } elseif (stripos($keyword, 'Programmer Raj') !== false) {
            echo json_encode([
                "status" => "success",
                "reply" => "I am Raj, a programmer and digital marketer. With 4 years of experience, I create Android apps and websites. I love helping people. YouTube channel: The Village Coder."
            ]);
            exit;
        } else {
            echo json_encode([
                "status" => "success",
                "reply" => "এটি একটি সাধারণ প্রশ্ন। আমি রাজ, একজন প্রোগ্রামার ও ডিজিটাল মার্কেটার।"
            ]);
            exit;
        }
    }
}

$data = [
    "model" => "llama-3.3-70b-versatile",
    "messages" => [
        ["role" => "user", "content" => $user_message]
    ]
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.groq.com/openai/v1/chat/completions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $api_key"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$result = json_decode($response, true);

if ($httpCode !== 200) {
    echo json_encode([
        "status" => "error",
        "code" => $httpCode,
        "message" => "API Error",
        "details" => $result
    ]);
} elseif (isset($result["choices"][0]["message"]["content"])) {
    echo json_encode([
        "status" => "success",
        "reply" => $result["choices"][0]["message"]["content"]
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "❌ উত্তর পাওয়া যায়নি বা API তে সমস্যা হচ্ছে।"
    ]);
}
?>
