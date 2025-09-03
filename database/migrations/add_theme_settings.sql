-- Migration: Add theme and font settings to restaurants table
-- Run this script to add new columns for theme customization

ALTER TABLE restaurants ADD COLUMN IF NOT EXISTS primary_font VARCHAR(100) DEFAULT 'Inter';
ALTER TABLE restaurants ADD COLUMN IF NOT EXISTS custom_font_name VARCHAR(255) DEFAULT NULL;
ALTER TABLE restaurants ADD COLUMN IF NOT EXISTS custom_font_path VARCHAR(255) DEFAULT NULL;
ALTER TABLE restaurants ADD COLUMN IF NOT EXISTS font_weights JSON DEFAULT NULL;
ALTER TABLE restaurants ADD COLUMN IF NOT EXISTS theme_settings JSON DEFAULT NULL;
ALTER TABLE restaurants ADD COLUMN IF NOT EXISTS features JSON DEFAULT NULL;

-- Update existing restaurants to have default values
UPDATE restaurants 
SET primary_font = COALESCE(primary_font, 'Inter'), 
    theme_settings = COALESCE(theme_settings, '{"mode": "light", "rounded": "medium"}'),
    features = COALESCE(features, '{}')
WHERE primary_font IS NULL OR theme_settings IS NULL OR features IS NULL;

-- Show updated structure
DESCRIBE restaurants;