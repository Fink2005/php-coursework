<?php
class VoteModel extends DB  {

    public function addVote($user_id, $post_id, $vote_type) {
        // Check if user has already voted
        $query = "SELECT vote_type FROM votes WHERE user_id = :user_id AND post_id = :post_id";
        $stmt = $this->con->prepare($query);
        $stmt->execute(['user_id' => $user_id, 'post_id' => $post_id]);
        $existingVote = $stmt->fetch(PDO::FETCH_ASSOC);

        $new_vote_type = null; // Track the resulting vote type
        if ($existingVote) {
            if ($existingVote['vote_type'] === $vote_type) {
                // Same vote type clicked again, remove the vote
                $query = "DELETE FROM votes WHERE user_id = :user_id AND post_id = :post_id";
                $stmt = $this->con->prepare($query);
                $stmt->execute(['user_id' => $user_id, 'post_id' => $post_id]);
                $message = 'Vote removed';
            } else {
                // Different vote type, remove the vote (no toggling)
                $query = "DELETE FROM votes WHERE user_id = :user_id AND post_id = :post_id";
                $stmt = $this->con->prepare($query);
                $stmt->execute(['user_id' => $user_id, 'post_id' => $post_id]);
                $message = 'Vote removed';
            }
        } else {
            // No existing vote, insert new vote only for upvote
            if ($vote_type === 'upvote') {
                $query = "INSERT INTO votes (user_id, post_id, vote_type) 
                          VALUES (:user_id, :post_id, :vote_type)";
                $stmt = $this->con->prepare($query);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->bindParam(':post_id', $post_id);
                $stmt->bindParam(':vote_type', $vote_type);
                $stmt->execute();
                $message = 'Vote recorded';
                $new_vote_type = 'upvote';
            } else {
                // Downvote with no existing vote, do nothing
                $message = 'No action taken';
            }
        }

        return [
            'success' => true,
            'message' => $message,
            'user_vote_type' => $new_vote_type
        ];
    }
    public function removeVote($user_id, $post_id) {
        $query = "DELETE FROM votes WHERE user_id = :user_id AND post_id = :post_id";
        $stmt = $this->con->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':post_id', $post_id);

        return $stmt->execute() ? ['success' => true, 'message' => 'Vote removed'] : 
                                  ['success' => false, 'message' => 'Failed to remove vote'];
    }

   public function getVoteCount($post_id) {
        $query = "SELECT 
                    COUNT(CASE WHEN vote_type = 'upvote' THEN 1 END) as upvotes,
                    COUNT(CASE WHEN vote_type = 'downvote' THEN 1 END) as downvotes
                  FROM votes WHERE post_id = :post_id";
        $stmt = $this->con->prepare($query);
        $stmt->execute(['post_id' => $post_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $upvotes = (int)($result['upvotes'] ?? 0);
        $downvotes = (int)($result['downvotes'] ?? 0);

        // Debugging: Log the raw vote data
        $debugQuery = "SELECT user_id, vote_type FROM votes WHERE post_id = :post_id";
        $debugStmt = $this->con->prepare($debugQuery);
        $debugStmt->execute(['post_id' => $post_id]);
        $debugVotes = $debugStmt->fetchAll(PDO::FETCH_ASSOC);
        error_log("Vote count for post_id $post_id: upvotes=$upvotes, downvotes=$downvotes, votes=" . json_encode($debugVotes));

        return [
            'upvotes' => $upvotes,
            'downvotes' => $downvotes
        ];
    }
}
?>