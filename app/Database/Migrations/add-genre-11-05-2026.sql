-- Add genre column to utilisateur table for gender selection
ALTER TABLE utilisateur ADD COLUMN IF NOT EXISTS genre VARCHAR(50);
