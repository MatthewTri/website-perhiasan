# AI Guided Options – Image Template

This folder contains placeholder image paths used by the guided AI chat UI.

## Base path

The UI reads images from:

- `public/asset/ai-options/...`

In Blade/JS, this is referenced as `asset('asset/ai-options')`.

## Category (step 1)

Put these files:

- `category/ring.jpg`
- `category/necklace-pendant.jpg`
- `category/earrings.jpg`
- `category/bracelet.jpg`

## Material (step 2) – per category

Ring:

- `ring/yellow-gold.png`
- `ring/white-gold.png`
- `ring/rose-gold.png`
- `ring/mixed.png`

Bracelet:

- `bracelet/yellow-gold.png`
- `bracelet/white-gold.png`
- `bracelet/rose-gold.png`
- `bracelet/mixed.png`

Earrings:

- `earrings/yellow-gold.png`
- `earrings/white-gold.png`
- `earrings/rose-gold.png`
- `earrings/mixed.png`

Necklace / Pendant:

- `necklace-pendant/yellow-gold.png`
- `necklace-pendant/white-gold.png`
- `necklace-pendant/rose-gold.png`
- `necklace-pendant/mixed.png`

Fallback (if category key not matched):

- `default/yellow-gold.png`
- `default/white-gold.png`
- `default/rose-gold.png`
- `default/mixed.png`

## Gemstone (step 3)

- `default/gemstone/diamond.png`
- `default/gemstone/sapphire.png`
- `default/gemstone/ruby.png`
- `default/gemstone/emerald.png`
- `default/gemstone/no-gemstone.png`

## Cut (step 4)

- `default/cut/round.png`
- `default/cut/oval.png`
- `default/cut/emerald.png`
- `default/cut/pear.png`
- `default/cut/princess.png`

## Style (step 5)

- `default/style/minimalist.png`
- `default/style/vintage.png`
- `default/style/nature.png`
- `default/style/royal.png`

## Notes

- Any image size works, but consistent aspect ratios look best.
- If you want category-specific gemstones/cuts/styles, extend the `optionCatalog` in the Blade view.
