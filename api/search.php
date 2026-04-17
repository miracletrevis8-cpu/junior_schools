<?php
require_once '../config.php';

header('Content-Type: application/json');

$query = sanitize($_GET['q'] ?? '');
$type = sanitize($_GET['type'] ?? 'all');

$results = [
    'events' => [],
    'students' => [],
    'news' => [],
    'vacancies' => []
];

if (strlen($query) < 2) {
    echo json_encode(['success' => true, 'results' => $results, 'total' => 0]);
    exit;
}

try {
    $searchTerm = "%{$query}%";

    if ($type === 'all' || $type === 'events') {
        $events = fetchAll("SELECT id, title, event_date as date, 'event' as type FROM events WHERE status = 'active' AND (title LIKE ? OR description LIKE ?) ORDER BY event_date DESC LIMIT 5", [$searchTerm, $searchTerm]);
        $results['events'] = $events;
    }

    if ($type === 'all' || $type === 'students') {
        $students = fetchAll("SELECT id, CONCAT(first_name, ' ', last_name) as title, grade as date, 'student' as type FROM students WHERE status = 'pending' AND (first_name LIKE ? OR last_name LIKE ? OR parent_name LIKE ?) ORDER BY created_at DESC LIMIT 5", [$searchTerm, $searchTerm, $searchTerm]);
        $results['students'] = $students;
    }

    if ($type === 'all' || $type === 'news') {
        $news = fetchAll("SELECT id, title, created_at as date, 'news' as type FROM news WHERE status = 'published' AND (title LIKE ? OR excerpt LIKE ?) ORDER BY created_at DESC LIMIT 5", [$searchTerm, $searchTerm]);
        $results['news'] = $news;
    }

    if ($type === 'all' || $type === 'vacancies') {
        $vacancies = fetchAll("SELECT id, title, department as date, 'vacancy' as type FROM vacancies WHERE is_active = 1 AND (title LIKE ? OR department LIKE ?) ORDER BY created_at DESC LIMIT 5", [$searchTerm, $searchTerm]);
        $results['vacancies'] = $vacancies;
    }

    $total = count($results['events']) + count($results['students']) + count($results['news']) + count($results['vacancies']);

    echo json_encode([
        'success' => true,
        'results' => $results,
        'total' => $total,
        'query' => $query
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}