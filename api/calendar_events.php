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
        
        // Convert date from dd/mm/yyyy to yyyy-mm-dd
        $eventDate = convertDate($corp['date'] ?? '');
        if (!$eventDate) continue;
        
        $timeStart = $corp['timeStart'] ?? '';
        $timeEnd = $corp['timeEnd'] ?? '';
        
        // 1. Main Event (showroom)
        $events[] = [
            'id' => 'main_' . $row['Id'],
            'title' => 'Showroom',
            'start' => $eventDate . ($timeStart ? 'T' . $timeStart : ''),
            'end' => $eventDate . ($timeEnd ? 'T' . $timeEnd : ''),
            'extendedProps' => [
                'visitorFormId' => $row['Id'],
                'docNo' => $row['DocNo'],
                'eventType' => 'Showroom',
                'timeStart' => $timeStart,
                'timeEnd' => $timeEnd,
                'detail' => ''
            ]
        ];
        
        // 2. Welcome Service Event (ป้ายต้อนรับ)
        if (!empty($corp['useWelcomeService'])) {
            $events[] = [
                'id' => 'welcome_' . $row['Id'],
                'title' => 'ป้ายต้อนรับ',
                'start' => $eventDate . ($corp['welcomeTimeStart'] ? 'T' . $corp['welcomeTimeStart'] : ''),
                'end' => $eventDate . ($corp['welcomeTimeEnd'] ? 'T' . $corp['welcomeTimeEnd'] : ''),
                'extendedProps' => [
                    'visitorFormId' => $row['Id'],
                    'docNo' => $row['DocNo'],
                    'eventType' => 'Welcome',
                    'timeStart' => $corp['welcomeTimeStart'] ?? '',
                    'timeEnd' => $corp['welcomeTimeEnd'] ?? '',
                    'detail' => $corp['welcomeDetail'] ?? ''
                ]
            ];
        }
        
        // 3. Photo Service Event (ถ่ายรูป)
        if (!empty($corp['usePhotoService'])) {
            $locations = $corp['photoLocations'] ?? [];
            $locationText = is_array($locations) ? implode(', ', $locations) : '';
            
            $events[] = [
                'id' => 'photo_' . $row['Id'],
                'title' => 'ถ่ายรูป',
                'start' => $eventDate . ($corp['photoTimeStart'] ? 'T' . $corp['photoTimeStart'] : ''),
                'end' => $eventDate . ($corp['photoTimeEnd'] ? 'T' . $corp['photoTimeEnd'] : ''),
                'extendedProps' => [
                    'visitorFormId' => $row['Id'],
                    'docNo' => $row['DocNo'],
                    'eventType' => 'Photo',
                    'timeStart' => $corp['photoTimeStart'] ?? '',
                    'timeEnd' => $corp['photoTimeEnd'] ?? '',
                    'detail' => 'สถานที่: ' . $locationText
                ]
            ];
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
