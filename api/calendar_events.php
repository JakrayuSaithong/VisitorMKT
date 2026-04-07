<?php
/**
 * API: calendar_events.php
 * ดึงข้อมูล events สำหรับ calendar จาก CorporateDetail
 */

include_once '../config/base.php';
header('Content-Type: application/json; charset=utf-8');

// Helper function to convert dd/mm/yyyy to yyyy-mm-dd
function convertDate($dateStr) {
    if (empty($dateStr)) return null;
    $parts = explode('/', $dateStr);
    if (count($parts) !== 3) return null;
    return $parts[2] . '-' . $parts[1] . '-' . $parts[0]; // yyyy-mm-dd
}

try {
    // ดึงข้อมูลจาก VisitorForm ทั้งหมด
    $sql = "SELECT Id, DocNo, CorporateDetail, Status
            FROM VisitorForm
            WHERE CorporateDetail IS NOT NULL 
            AND Status IN (3, 5, 6)
            ORDER BY Id DESC";
    
    $stmt = sqlsrv_query($konnext_DB64, $sql);
    
    if (!$stmt) {
        throw new Exception(print_r(sqlsrv_errors(), true));
    }
    
    $events = [];
    
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        if (empty($row['CorporateDetail'])) continue;
        
        $corp = json_decode($row['CorporateDetail'], true);
        if (!$corp) continue;
        
        $entries = [];
        if (isset($corp['entries']) && is_array($corp['entries'])) {
            $entries = $corp['entries'];
        } else if (isset($corp['date'])) {
            $entries[] = $corp;
        }

        foreach ($entries as $index => $entry) {
            // Convert date from dd/mm/yyyy to yyyy-mm-dd
            $eventDate = convertDate($entry['date'] ?? '');
            if (!$eventDate) continue;
            
            // 1. Welcome Service Event (ป้ายต้อนรับ)
            if (!empty($entry['useWelcomeService'])) {
                $timeStartStr = !empty($entry['welcomeTimeStart']) ? ('T' . $entry['welcomeTimeStart']) : '';
                $timeEndStr = !empty($entry['welcomeTimeEnd']) ? ('T' . $entry['welcomeTimeEnd']) : '';
                $events[] = [
                    'id' => 'welcome_' . $row['Id'] . '_' . $index,
                    'title' => 'ป้ายต้อนรับ',
                    'start' => $eventDate . $timeStartStr,
                    'end' => $eventDate . $timeEndStr,
                    'extendedProps' => [
                        'visitorFormId' => $row['Id'],
                        'docNo' => $row['DocNo'],
                        'eventType' => 'Welcome',
                        'timeStart' => $entry['welcomeTimeStart'] ?? '',
                        'timeEnd' => $entry['welcomeTimeEnd'] ?? '',
                        'detail' => $entry['welcomeDetail'] ?? ''
                    ]
                ];
            }
            
            // 3. Photo Service Event (ถ่ายรูป)
            if (!empty($entry['usePhotoService'])) {
                $locations = $entry['photoLocations'] ?? [];
                $locationText = is_array($locations) ? implode(', ', $locations) : '';
                
                $timeStartStr = !empty($entry['photoTimeStart']) ? ('T' . $entry['photoTimeStart']) : '';
                $timeEndStr = !empty($entry['photoTimeEnd']) ? ('T' . $entry['photoTimeEnd']) : '';
                $events[] = [
                    'id' => 'photo_' . $row['Id'] . '_' . $index,
                    'title' => 'ถ่ายรูป',
                    'start' => $eventDate . $timeStartStr,
                    'end' => $eventDate . $timeEndStr,
                    'extendedProps' => [
                        'visitorFormId' => $row['Id'],
                        'docNo' => $row['DocNo'],
                        'eventType' => 'Photo',
                        'timeStart' => $entry['photoTimeStart'] ?? '',
                        'timeEnd' => $entry['photoTimeEnd'] ?? '',
                        'detail' => 'สถานที่: ' . $locationText
                    ]
                ];
            }
        }
    }
    
    echo json_encode([
        "status" => true,
        "events" => $events
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    echo json_encode([
        "status" => false,
        "message" => $e->getMessage()
    ]);
}
