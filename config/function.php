<?php

function incrementOfferViews($offer_id, $connection) {
    $sql1 = "INSERT INTO statistics (date, offer_impressions) VALUES (NOW(), 1) ON DUPLICATE KEY UPDATE offer_impressions = offer_impressions + 1";
    $stmt1 = $connection->prepare($sql1);
    $stmt1->execute();

    $sql2 = "UPDATE offers SET offer_impressions = offer_impressions + 1 WHERE id = ?";
    $stmt2 = $connection->prepare($sql2);
    $stmt2->bind_param('i', $offer_id);
    $stmt2->execute();

    $stmt1->close();
    $stmt2->close();
}

function getViewedNews() {
    if (isset($_COOKIE['viewed_news'])) {
        $decoded = json_decode($_COOKIE['viewed_news'], true);
        return is_array($decoded) ? $decoded : array();
    }
    return array();
}

function updateViewedNews($id) {
    $viewed_news = getViewedNews();
    if (!in_array($id, $viewed_news)) {
        $viewed_news[] = (int)$id;
        $cookie_lifetime = time() + (60 * 60 * 24);
        // Исправлено: добавлен путь '/', чтобы куки работали в подпапках
        setcookie('viewed_news', json_encode($viewed_news), $cookie_lifetime, '/');
    }
}

function getUserIp(){
    $ip_url = 'https://api.ipify.org?format=json';
    $ip_json = file_get_contents($ip_url);
    $ip_data = json_decode($ip_json, true);
    return isset($ip_data['ip']) ? $ip_data['ip'] : $_SERVER['REMOTE_ADDR'];
}

function getUserCountry() {
    $ip = getUserIp();
    $url = "http://ip-api.com/json/{$ip}?fields=status,countryCode";
    $json = file_get_contents($url);
    $data = json_decode($json, true);
    return ($data['status'] == 'success') ? $data['countryCode'] : null;
}

function replace_macros($offer_link, $nid, $oid) {
    $replaced_link = str_replace('{nid}', $nid, $offer_link);
    $replaced_link = str_replace('{oid}', $oid, $replaced_link);
    return $replaced_link;
}