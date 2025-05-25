<?php 
declare(strict_types=1);


class Message {
    private PDO $db; 

    public function __construct(PDO $database){
        $this->db = $database;
    }

    public function getListofConversations(int $user_id){
        $stmt = $this->db->prepare("
            WITH latest AS (
                SELECT id,
                    CASE WHEN sender = :me THEN receiver ELSE sender END AS other_id
                FROM Messages
                WHERE sender = :me OR receiver = :me
                GROUP BY other_id
                HAVING MAX(timeStamp)
            )
            SELECT m.id,
                m.text,
                m.timeStamp,
                m.sender,
                -- figure out who the other user is for this row
                CASE WHEN m.sender = :me THEN m.receiver ELSE m.sender END AS other_user_id,
                u.username,
                u.profileImage
            FROM Messages m
            JOIN latest l ON l.id = m.id
            JOIN User u   ON u.id = (CASE WHEN m.sender = :me THEN m.receiver ELSE m.sender END)
            ORDER BY m.timeStamp DESC
        ");
        $stmt->execute(['me' => $user_id]);
        $conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $conversations;
    }

    public function getConversation(int $user_id, int $other){
        $stmt = $this->db->prepare(
            "SELECT id, sender, text, timeStamp
            FROM Messages
            WHERE (sender = :me AND receiver = :other)
            OR (sender = :other AND receiver = :me)
            ORDER BY id ASC"
        );
        $stmt->execute(['me' => $user_id, 'other' => $other]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    public function getNewMessages(int $user_id, int $other, int $after){
        $stmt = $this->db->prepare(
            "SELECT id, sender, text, timeStamp
            FROM Messages
            WHERE ((sender = :me AND receiver = :other)
                OR (sender = :other AND receiver = :me))
                AND id > :after
            ORDER BY id ASC"
        );
        $stmt->execute([
            'me'    => $user_id,
            'other' => $other,
            'after' => $after
        ]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }
    public function sendNewMessage(int $user_id, int $other, string $text){
        $stmt = $this->db->prepare("SELECT id FROM User WHERE id = :other_id");
        $stmt->execute(['other_id' => $other]);
        if (!$stmt->fetch()) {
            throw new Exception('recipient user not found');
        }

        $stmt = $this->db->prepare(
            "INSERT INTO Messages (sender, receiver, text)
                VALUES (:me, :other, :text)"
        );
        $stmt->execute([
            'me'    => $user_id,
            'other' => $other,
            'text'  => $text
        ]);

        $newId = $this->db->lastInsertId();
        return $newId;
    }
}
?>
