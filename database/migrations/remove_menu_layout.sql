-- Migration: Remove menu_layout column from restaurants table
-- This removes the advanced menu layout option, keeping only the standard expandable categories layout

ALTER TABLE restaurants DROP COLUMN IF EXISTS menu_layout;

-- Show updated structure
DESCRIBE restaurants;