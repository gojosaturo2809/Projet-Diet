#!/bin/bash

BASE_URL="http://localhost:8001"

# Create session file for the test
SESSION_FILE=$(mktemp)
echo "Testing with session file: $SESSION_FILE"

# Step 1: Get inscription page and set initial session
echo "=== Step 1: Inscription page ==="
RESPONSE=$(curl -s -c "$SESSION_FILE" "$BASE_URL/index.php/inscription")
echo "Status: OK (received HTML)"

# Step 2: Register health info
echo -e "\n=== Step 2: Register health info ==="
RESPONSE=$(curl -s -b "$SESSION_FILE" -c "$SESSION_FILE" \
  -X POST "$BASE_URL/index.php/inscription/sante" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "taille=170&poids=85&age=30&sexe=M")
echo "$RESPONSE" | php -r 'echo json_encode(json_decode(file_get_contents("php://stdin"), true), JSON_PRETTY_PRINT);'

# Extract user ID from session
SESSION_ID=$(grep -oP 'PHPSESSID=\K[^;]+' "$SESSION_FILE" | head -1)
echo "Session ID: $SESSION_ID"

# Step 3: Complete inscription
echo -e "\n=== Step 3: Complete inscription ==="
RESPONSE=$(curl -s -b "$SESSION_FILE" -c "$SESSION_FILE" \
  -X POST "$BASE_URL/index.php/inscription/inscription" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "email=test@example.com&nom=Test&prenom=User&password=Pass1234")
echo "Status: Redirect check"

# Step 4: Select objective 1 (Perte de poids) with low target weight
echo -e "\n=== Step 4: Select objective 1 (Perte de poids) with weight 50kg (IMC will be < 18.5) ==="
RESPONSE=$(curl -s -b "$SESSION_FILE" -c "$SESSION_FILE" -w "\nHTTP_STATUS:%{http_code}\n" \
  -X POST "$BASE_URL/index.php/objectifs/selectionner" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "id_objectif=1&poids_cible=50&duree_objectif_semaine=12")

HTTP_STATUS=$(echo "$RESPONSE" | grep "HTTP_STATUS" | cut -d: -f2)
echo "HTTP Status: $HTTP_STATUS"
if [ "$HTTP_STATUS" = "303" ] || [ "$HTTP_STATUS" = "302" ]; then
  echo "SUCCESS: Received redirect (expected behavior)"
else
  echo "ERROR: Did not receive redirect"
  echo "$RESPONSE" | head -20
fi

# Step 5: Test objective 2 (Prise de muscle)
echo -e "\n=== Step 5: Select objective 2 (Prise de muscle) ==="
RESPONSE=$(curl -s -b "$SESSION_FILE" -c "$SESSION_FILE" -w "\nHTTP_STATUS:%{http_code}\n" \
  -X POST "$BASE_URL/index.php/objectifs/selectionner" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "id_objectif=2&poids_cible=75&duree_objectif_semaine=8")

HTTP_STATUS=$(echo "$RESPONSE" | grep "HTTP_STATUS" | cut -d: -f2)
echo "HTTP Status: $HTTP_STATUS"
if [ "$HTTP_STATUS" = "303" ] || [ "$HTTP_STATUS" = "302" ]; then
  echo "SUCCESS: Received redirect (expected behavior)"
else
  echo "ERROR: Did not receive redirect"
  echo "$RESPONSE" | head -20
fi

rm -f "$SESSION_FILE"
echo -e "\nTest complete!"
