<?php

namespace Database\Seeders;

use App\Models\Enquiry;
use App\Models\Package;
use App\Models\PackageDay;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Real itineraries sourced from https://flyseastravels.com/
 * Slugs match the live site's permalinks (e.g. /trips/bali-bliss-honeymoon-escape).
 * Prices are sensible estimates for the Indian travel market — the live site
 * doesn't publish prices, so update from admin after confirming with FlySeas.
 */
class PackageSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        PackageDay::truncate();
        Enquiry::truncate();
        Package::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ─────────────────────────────────────────────────────────────
        // 1. BALI BLISS HONEYMOON ESCAPE
        // ─────────────────────────────────────────────────────────────
        $bali = Package::create([
            'title'            => 'Bali Bliss Honeymoon Escape',
            'slug'             => 'bali-bliss-honeymoon-escape',
            'location'         => 'Bali',
            'country'          => 'Indonesia',
            'category'         => 'honeymoon',
            'badge'            => 'bestseller',
            'days'             => 5,
            'nights'           => 4,
            'tier'             => 'premium',
            'price_per_person' => 52999,
            'rating'           => 4.9,
            'review_count'     => 124,
            'description'      => 'Five romantic days in Bali — beaches, temples and a private pool villa.',
            'overview'         => 'A romantic honeymoon escape to the Island of Love. Splash through watersports at Benoa Beach, soak in the sunset at Uluwatu Temple with the Kecak fire dance, explore Ubud\'s art villages and the iconic Kelingking Beach, and end the trip in your own private pool villa with a floating breakfast for two.',
            'highlights'       => [
                ['title' => 'Private Pool Villa',          'description' => 'Two nights in a luxury private pool villa with floating breakfast.'],
                ['title' => 'Uluwatu Sunset & Fire Dance', 'description' => 'Cliff-top temple at golden hour with the Kecak & Fire Dance performance.'],
                ['title' => 'Benoa Beach Watersports',     'description' => 'Complimentary jet ski, banana boat and parasailing at Benoa.'],
                ['title' => 'Kelingking Beach Viewpoint',  'description' => 'The iconic T-Rex shaped cliff and turquoise lagoon in Nusa Penida.'],
            ],
            'inclusions'       => [
                '4 nights twin-sharing accommodation (2 hotel + 2 pool villa nights)',
                'Daily breakfast',
                'Welcome flower garland on arrival',
                'English-speaking driver throughout',
                'Daily water bottles per person',
                'Airport pickup and drop',
                'All sightseeing & transfers on private basis',
                'All applicable Indonesia taxes',
            ],
            'exclusions'       => [
                'GST (5%) and TCS (5%)',
                'International airfare and train tickets',
                'Indonesia E-Visa (₹3,200 per person)',
                'Private guide services',
                'Harbor taxes for ferry trips',
                'Meals beyond breakfast',
                'Adventure sports & shopping',
                'Expenses from delays / natural causes',
            ],
            'includes_icons'   => ['hotel', 'meals', 'transfers'],
            // Bali — Uluwatu cliff, rice terrace, beach, pool villa
            'hero_image'       => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=1600&q=85',
            'gallery_images'   => [
                'https://images.unsplash.com/photo-1518002171953-a080ee817e1f?w=900&q=85', // Uluwatu cliff
                'https://images.unsplash.com/photo-1604999333679-b86d54738315?w=900&q=85', // Bali temple
                'https://images.unsplash.com/photo-1531592937781-344ad608fabf?w=900&q=85', // Kelingking
                'https://images.unsplash.com/photo-1555400038-63f5ba517a47?w=900&q=85',    // pool villa
                'https://images.unsplash.com/photo-1518548419970-58e3b4079ab2?w=900&q=85', // tropical
            ],
            'is_featured'      => true,
            'is_active'        => true,
            'sort_order'       => 1,
            'seats_left'       => null,
        ]);

        $this->createDays($bali->id, [
            [
                'title'       => 'Welcome to Bali — The Island of Love',
                'location'    => 'Denpasar Airport → Hotel',
                'description' => 'Land in Bali and meet your driver at arrivals with a fresh flower garland. Private transfer to your hotel for check-in. Spend the rest of the evening relaxing or exploring the local neighbourhood at your own pace.',
                'activities'  => [
                    ['time' => 'On arrival', 'text' => 'Airport meet-and-greet with welcome garland'],
                    ['time' => 'Afternoon',  'text' => 'Hotel check-in and freshen up'],
                    ['time' => 'Evening',    'text' => 'Free time to relax or explore the neighbourhood'],
                ],
            ],
            [
                'title'       => 'Adventure & Sunset at Uluwatu',
                'location'    => 'Benoa Beach & Uluwatu',
                'description' => 'Morning at Benoa Beach with complimentary jet ski, banana boat and parasailing. After lunch, head to the cliff-top Uluwatu Temple for the famous sunset and the Kecak & Fire Dance show.',
                'activities'  => [
                    ['time' => '09:30', 'text' => 'Jet ski, banana boat & parasailing at Benoa Beach'],
                    ['time' => '15:00', 'text' => 'Drive to Uluwatu Temple'],
                    ['time' => '17:30', 'text' => 'Sunset at the temple cliff'],
                    ['time' => '18:30', 'text' => 'Kecak & Fire Dance performance'],
                ],
            ],
            [
                'title'       => 'Ubud Exploration & Private Villa Stay',
                'location'    => 'Kelingking Beach → Ubud → Villa',
                'description' => 'Visit the iconic Kelingking Beach viewpoint, then explore Ubud\'s Art Market and the handicraft villages of Celuk and Mas. Stop for photos at the Bali Swing before transferring to your private pool villa.',
                'activities'  => [
                    ['time' => '08:00', 'text' => 'Kelingking Beach viewpoint'],
                    ['time' => '11:30', 'text' => 'Ubud Art Market & handicraft villages'],
                    ['time' => '14:00', 'text' => 'Photo session at the Bali Swing'],
                    ['time' => '17:00', 'text' => 'Check-in at your private pool villa'],
                ],
            ],
            [
                'title'       => 'Romantic Day at Leisure',
                'location'    => 'Private Pool Villa',
                'description' => 'A full day reserved for the two of you. Wake up to a romantic floating breakfast in your pool, then spend the day at your own pace — café-hop, book a spa treatment, or simply unwind by the water.',
                'activities'  => [
                    ['time' => 'Morning',   'text' => 'Floating breakfast in the villa pool'],
                    ['time' => 'Afternoon', 'text' => 'Free time — café exploration or spa'],
                    ['time' => 'Evening',   'text' => 'Sunset relaxation at the villa'],
                ],
            ],
            [
                'title'       => 'Departure with Memories',
                'location'    => 'Villa → Denpasar Airport',
                'description' => 'Enjoy a leisurely breakfast at your villa, check out, and transfer to the airport for your flight home — taking back unforgettable memories of Bali.',
                'activities'  => [
                    ['time' => 'Morning', 'text' => 'Breakfast and villa check-out'],
                    ['time' => 'Onward',  'text' => 'Private airport transfer for departure'],
                ],
            ],
        ]);

        // ─────────────────────────────────────────────────────────────
        // 2. NEW YEAR HEAVENLY MANALI GROUP TOUR FROM NAGPUR
        // ─────────────────────────────────────────────────────────────
        $manaliNY = Package::create([
            'title'            => 'New Year Heavenly Manali Group Tour from Nagpur',
            'slug'             => 'new-year-heavenly-manali-group-tour-from-nagpur',
            'location'         => 'Manali',
            'country'          => 'India',
            'category'         => 'group',
            'badge'            => 'limited',
            'days'             => 9,
            'nights'           => 8,
            'tier'             => 'standard',
            'price_per_person' => 19999,
            'rating'           => 4.7,
            'review_count'     => 86,
            'description'      => 'Ring in the New Year in the Himalayas — train from Nagpur, group adventure, DJ night.',
            'overview'         => 'A complete New Year escape from Nagpur to the snow-capped Himalayas. Travel by Chhattisgarh Express, settle in Manali for three nights, explore Solang Valley, Atal Tunnel and Rohtang, then head to Kasol & Kullu for adventure and Manikaran. Includes a DJ night and bonfire on 31st December.',
            'highlights'       => [
                ['title' => 'New Year DJ Night',         'description' => 'Bonfire and DJ celebration in Manali on 31st December.'],
                ['title' => 'Solang Valley & Rohtang',   'description' => 'Snow valley with optional Atal Tunnel & Rohtang Pass excursion.'],
                ['title' => 'Manikaran Sahib',           'description' => 'Hot springs and Gurudwara langar at the holy Manikaran shrine.'],
                ['title' => 'Train from Nagpur',         'description' => 'Round-trip Chhattisgarh Express tickets — no flight booking hassle.'],
            ],
            'inclusions'       => [
                '3-star hotel accommodation on quad-sharing basis',
                'Return sleeper-class train tickets (Chhattisgarh Express)',
                '3 nights at Manali hotel',
                'Local sightseeing in Manali',
                'Solang Valley & Atal Tunnel excursion',
                'Kullu & Kasol sightseeing',
                'Manikaran Sahib visit',
                'New Year DJ night party with bonfire',
                'Daily breakfast and dinner as per itinerary',
                'Tour manager (Hindi / Marathi / English)',
                'Tempo Traveller transport',
                'All transfers, tolls, fuel, parking, taxes',
            ],
            'exclusions'       => [
                'Air fare',
                '5% GST on bill',
                'Train food',
                'Meals not specified in itinerary',
                'Adventure activities & snow-dress rental',
                'Atal Tunnel & Rohtang Pass excursion (₹1,500 per person)',
                'Personal shopping',
                'Disruptions from flight / rail delays, weather, breakdowns',
            ],
            'includes_icons'   => ['hotel', 'meals', 'transfers'],
            // Manali / Solang — snow & mountain landscapes
            'hero_image'       => 'https://images.unsplash.com/photo-1626621341517-bbf3d9990a23?w=1600&q=85',
            'gallery_images'   => [
                'https://images.unsplash.com/photo-1597074866923-dc0589150358?w=900&q=85', // snow valley
                'https://images.unsplash.com/photo-1580188897697-6ab9d3e75e3e?w=900&q=85', // himalayan view
                'https://images.unsplash.com/photo-1516406742981-2b7d67ec4ae8?w=900&q=85', // mountain village
                'https://images.unsplash.com/photo-1605649487212-47bdab064df7?w=900&q=85', // mountain road
                'https://images.unsplash.com/photo-1573820598926-65f7e5fe6cae?w=900&q=85', // snow trek
            ],
            'is_featured'      => true,
            'is_active'        => true,
            'sort_order'       => 2,
            'seats_left'       => 8,
        ]);

        $this->createDays($manaliNY->id, [
            [
                'title'       => 'Departure from Nagpur',
                'location'    => 'Nagpur Railway Station',
                'description' => 'Board Chhattisgarh Express (18237) at 22:20 from Nagpur Railway Station for the overnight journey to Ambala.',
                'activities'  => [
                    ['time' => '21:30', 'text' => 'Reporting at Nagpur Railway Station'],
                    ['time' => '22:20', 'text' => 'Board Chhattisgarh Express (18237)'],
                ],
            ],
            [
                'title'       => 'Ambala to Manali',
                'location'    => 'Ambala Cantt → Manali',
                'description' => 'Arrive at Ambala Cantt and transfer by Tempo Traveller to Manali. Scenic overnight drive through the Himachal mountains. Dinner en route.',
                'activities'  => [
                    ['time' => 'Morning', 'text' => 'Arrival at Ambala Cantt Railway Station'],
                    ['time' => 'Day',     'text' => 'Transfer to Manali via the Himachal hills'],
                    ['time' => 'Evening', 'text' => 'Dinner en route'],
                ],
            ],
            [
                'title'       => 'Manali Local Sightseeing',
                'location'    => 'Manali',
                'description' => 'Check in and rest. Then visit Hadimba Devi Temple, Club House and Karthik Swami Temple. Explore Manali village and Mall Road for shopping.',
                'activities'  => [
                    ['time' => 'Morning',   'text' => 'Hotel check-in and rest'],
                    ['time' => 'Afternoon', 'text' => 'Hadimba Devi Temple, Club House, Karthik Swami Temple'],
                    ['time' => 'Evening',   'text' => 'Mall Road and Manali village shopping'],
                ],
            ],
            [
                'title'       => 'Solang Valley & New Year Eve',
                'location'    => 'Solang Valley → Hotel',
                'description' => 'Day at Solang Valley with optional adventure activities (paragliding, skiing, ATV — at extra cost). Optional Atal Tunnel & Rohtang Pass excursion (₹1,500). Tonight, ring in the New Year with a DJ night and bonfire celebration at the hotel.',
                'activities'  => [
                    ['time' => 'Morning',   'text' => 'Drive to Solang Valley'],
                    ['time' => 'Day',       'text' => 'Optional paragliding, skiing, ATV rides'],
                    ['time' => 'Optional',  'text' => 'Atal Tunnel & Rohtang Pass (₹1,500 per person)'],
                    ['time' => 'Night',     'text' => 'New Year DJ night and bonfire at hotel'],
                ],
            ],
            [
                'title'       => 'Kasol & Kullu Adventure',
                'location'    => 'Kullu → Kasol → Manikaran',
                'description' => 'Drive to Kasol via Kullu. Optional river rafting (₹600–1,000) and paragliding (₹2,000–2,500). Visit shawl factory and dry-fruit shops. Visit Manikaran Sahib Gurudwara and Shiv Temple, with langar lunch. Return to Manali for the night.',
                'activities'  => [
                    ['time' => 'Morning',   'text' => 'Drive to Kasol via Kullu'],
                    ['time' => 'Optional',  'text' => 'River rafting and paragliding'],
                    ['time' => 'Midday',    'text' => 'Shawl factory & dry-fruit shops'],
                    ['time' => 'Afternoon', 'text' => 'Manikaran Sahib Gurudwara and Shiv Temple langar'],
                    ['time' => 'Evening',   'text' => 'Return drive to Manali hotel'],
                ],
            ],
            [
                'title'       => 'Departure from Manali',
                'location'    => 'Manali → Ambala Cantt',
                'description' => 'Early breakfast and check-out. 7–8 hour drive back to Ambala Cantt to board Chhattisgarh Express (18238) at 21:40.',
                'activities'  => [
                    ['time' => 'Early',    'text' => 'Breakfast and hotel check-out'],
                    ['time' => 'Day',      'text' => 'Drive Manali → Ambala Cantt (7–8 hrs)'],
                    ['time' => '21:40',    'text' => 'Board Chhattisgarh Express (18238)'],
                ],
            ],
            [
                'title'       => 'In Transit',
                'location'    => 'On board Chhattisgarh Express',
                'description' => 'Full day on the train heading back towards Nagpur.',
                'activities'  => [
                    ['time' => 'All day', 'text' => 'On the train, relaxing journey home'],
                ],
            ],
            [
                'title'       => 'Arrival at Nagpur',
                'location'    => 'Nagpur Railway Station',
                'description' => 'Morning arrival at Nagpur. Tour ends here with a heart full of Himalayan memories.',
                'activities'  => [
                    ['time' => 'Morning', 'text' => 'Arrival at Nagpur Railway Station — tour concludes'],
                ],
            ],
        ]);

        // ─────────────────────────────────────────────────────────────
        // 3. ESCAPE KERALA PACKAGE
        // ─────────────────────────────────────────────────────────────
        $escapeKerala = Package::create([
            'title'            => 'Escape Kerala Package',
            'slug'             => 'escape-kerala-package',
            'location'         => 'Kerala',
            'country'          => 'India',
            'category'         => 'honeymoon',
            'badge'            => 'bestseller',
            'days'             => 6,
            'nights'           => 5,
            'tier'             => 'premium',
            'price_per_person' => 34499,
            'rating'           => 4.8,
            'review_count'     => 142,
            'description'      => 'Six days of waterfalls, tea hills, wildlife and a private houseboat in God\'s Own Country.',
            'overview'         => 'A complete Kerala journey covering Cochin\'s port heritage, the Athirapally and Cheeyappara waterfalls, Munnar\'s tea hills, Thekkady\'s spice forests and a private houseboat night on the Alleppey backwaters. Ideal for couples and slow travellers.',
            'highlights'       => [
                ['title' => 'Athirapally Waterfalls',     'description' => 'The "Niagara of India" en route to Munnar — perfect photo stop.'],
                ['title' => 'Munnar Tea Plantations',     'description' => 'Mattupetty Dam, Kundala Lake and rolling tea estates.'],
                ['title' => 'Periyar Wildlife Boat Ride', 'description' => 'Spot elephants and bison from a boat on Periyar Lake (optional).'],
                ['title' => 'Alleppey Houseboat',         'description' => 'Private houseboat overnight cruise through palm-lined backwaters.'],
            ],
            'inclusions'       => [
                '5 nights double-sharing accommodation across 4 properties',
                'Breakfast and dinner as per itinerary',
                'All houseboat meals (breakfast, lunch, dinner)',
                'Private vehicle for sightseeing and transfers',
                'Airport / railway station pickup and drop',
                'Driver allowance, tolls, parking, fuel',
                '5% GST',
            ],
            'exclusions'       => [
                'Flight or train tickets',
                'Meals outside the itinerary',
                'Eravikulam National Park entrance fees',
                'Optional honeymoon kit (₹3,500)',
                'Shopping and adventure sports',
                'Expenses from delays / unforeseen events',
            ],
            'includes_icons'   => ['hotel', 'meals', 'transfers'],
            // Kerala — Alleppey backwaters, Munnar tea, houseboat
            'hero_image'       => 'https://images.unsplash.com/photo-1602216056096-3b40cc0c9944?w=1600&q=85',
            'gallery_images'   => [
                'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=900&q=85', // houseboat
                'https://images.unsplash.com/photo-1596895111956-bf1cf0599ce5?w=900&q=85', // tea estate
                'https://images.unsplash.com/photo-1582510003544-4d00b7f74220?w=900&q=85', // Munnar hills
                'https://images.unsplash.com/photo-1609340040223-3a1d3a36cab6?w=900&q=85', // backwater
                'https://images.unsplash.com/photo-1623876174519-bf6a04a8a08a?w=900&q=85', // palms
            ],
            'is_featured'      => true,
            'is_active'        => true,
            'sort_order'       => 3,
            'seats_left'       => null,
        ]);

        $this->createDays($escapeKerala->id, [
            [
                'title'       => 'Welcome to Cochin',
                'location'    => 'Cochin (Kochi)',
                'description' => 'Airport / railway station pickup and transfer to your Cochin hotel. Check in and use the rest of the day to explore the historic port city at your own pace. Dinner at the hotel.',
                'activities'  => [
                    ['time' => 'On arrival', 'text' => 'Pickup and transfer to hotel'],
                    ['time' => 'Afternoon',  'text' => 'Leisure time in Cochin'],
                    ['time' => 'Evening',    'text' => 'Dinner at hotel'],
                ],
            ],
            [
                'title'       => 'Cochin to Munnar',
                'location'    => 'Cochin → Munnar',
                'description' => 'Visit the Chinese Fishing Nets and Athirapally Waterfalls. Stop at Cheeyappara Waterfalls en route. Arrive Munnar by evening, check in and rest.',
                'activities'  => [
                    ['time' => 'Morning',   'text' => 'Chinese Fishing Nets and Athirapally Waterfalls'],
                    ['time' => 'Midday',    'text' => 'Cheeyappara Waterfalls en route'],
                    ['time' => 'Evening',   'text' => 'Munnar hotel check-in and dinner'],
                ],
            ],
            [
                'title'       => 'Munnar Sightseeing',
                'location'    => 'Munnar',
                'description' => 'Mattupetty Dam, Kundala Lake boat ride or sightseeing, tea plantation visits and scenic photo points. Optional Eravikulam National Park (closed Feb–Mar).',
                'activities'  => [
                    ['time' => 'Morning',   'text' => 'Mattupetty Dam and Kundala Lake'],
                    ['time' => 'Afternoon', 'text' => 'Tea plantation walk & photo points'],
                    ['time' => 'Optional',  'text' => 'Eravikulam National Park (paid separately)'],
                ],
            ],
            [
                'title'       => 'Munnar to Thekkady',
                'location'    => 'Western Ghats → Thekkady',
                'description' => 'Drive through the scenic Western Ghats to Thekkady. Optional Periyar Lake boat ride for wildlife viewing in the evening.',
                'activities'  => [
                    ['time' => 'Morning',   'text' => 'Drive Munnar to Thekkady'],
                    ['time' => 'Optional',  'text' => 'Periyar Lake boat ride for wildlife'],
                    ['time' => 'Evening',   'text' => 'Hotel check-in and dinner'],
                ],
            ],
            [
                'title'       => 'Thekkady to Alleppey Houseboat',
                'location'    => 'Alleppey Backwaters',
                'description' => 'Transfer to Alleppey and board your private houseboat. Cruise through palm-lined backwater canals with all meals on board. Overnight on the boat.',
                'activities'  => [
                    ['time' => 'Morning',   'text' => 'Transfer to Alleppey'],
                    ['time' => 'Noon',      'text' => 'Board private houseboat — lunch on board'],
                    ['time' => 'Evening',   'text' => 'Sunset cruise and dinner on the boat'],
                ],
            ],
            [
                'title'       => 'Departure',
                'location'    => 'Alleppey → Cochin',
                'description' => 'Morning checkout from the houseboat. Transfer to Cochin Airport / Railway Station for departure.',
                'activities'  => [
                    ['time' => 'Morning', 'text' => 'Breakfast and houseboat checkout'],
                    ['time' => 'Onward',  'text' => 'Transfer to Cochin Airport / Railway Station'],
                ],
            ],
        ]);

        // ─────────────────────────────────────────────────────────────
        // 4. EXPLORE KERALA
        // ─────────────────────────────────────────────────────────────
        $exploreKerala = Package::create([
            'title'            => 'Explore Kerala',
            'slug'             => 'explore-kerala',
            'location'         => 'Kerala',
            'country'          => 'India',
            'category'         => 'honeymoon',
            'badge'            => 'new',
            'days'             => 5,
            'nights'           => 4,
            'tier'             => 'standard',
            'price_per_person' => 24999,
            'rating'           => 4.7,
            'review_count'     => 73,
            'description'      => 'Five-day Kerala discovery — Munnar tea hills, Thekkady wildlife and a houseboat cruise.',
            'overview'         => 'A shorter, well-paced Kerala route through Munnar\'s tea estates, Thekkady\'s Periyar wildlife sanctuary and the unforgettable backwaters of Alleppey on a private houseboat. Perfect for couples or first-time visitors to God\'s Own Country.',
            'highlights'       => [
                ['title' => 'Cheeyappara Waterfalls',  'description' => 'Roadside cascade en route from Cochin to Munnar.'],
                ['title' => 'Mattupetty & Kundala',    'description' => 'Photo points, tea estates and scenic mountain drives.'],
                ['title' => 'Periyar Wildlife',        'description' => 'Optional boat ride for elephants, bison and birds.'],
                ['title' => 'Alleppey Houseboat',      'description' => 'Private kettuvallam cruise through coconut-lined canals.'],
            ],
            'inclusions'       => [
                '4 nights double-sharing accommodation',
                '2 nights Munnar, 1 night Thekkady, 1 night Alleppey houseboat',
                'Breakfast and dinner as per itinerary',
                'All sightseeing & transfers via private sedan',
                'Airport / railway station pickup and drop',
                'Driver, tolls, parking, fuel and interstate fees',
                '5% GST',
            ],
            'exclusions'       => [
                'Train or air fare',
                'Meals not specified in the package',
                'Optional honeymoon kit (₹3,500 extra)',
                'Shopping, adventure sports, additional sightseeing',
                'Expenses from unforeseen circumstances',
            ],
            'includes_icons'   => ['hotel', 'meals', 'transfers'],
            // Kerala — Munnar focus
            'hero_image'       => 'https://images.unsplash.com/photo-1582510003544-4d00b7f74220?w=1600&q=85',
            'gallery_images'   => [
                'https://images.unsplash.com/photo-1602216056096-3b40cc0c9944?w=900&q=85', // backwaters
                'https://images.unsplash.com/photo-1596895111956-bf1cf0599ce5?w=900&q=85', // tea estate
                'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=900&q=85',    // houseboat
                'https://images.unsplash.com/photo-1609340040223-3a1d3a36cab6?w=900&q=85', // canals
            ],
            'is_featured'      => false,
            'is_active'        => true,
            'sort_order'       => 4,
            'seats_left'       => null,
        ]);

        $this->createDays($exploreKerala->id, [
            [
                'title'       => 'Welcome to Kerala',
                'location'    => 'Cochin → Munnar',
                'description' => 'Airport / railway station pickup and 4–5 hour drive to Munnar. Visit Cheeyappara Waterfalls en route. Hotel check-in and evening at leisure.',
                'activities'  => [
                    ['time' => 'On arrival', 'text' => 'Pickup and transfer towards Munnar'],
                    ['time' => 'Midway',     'text' => 'Cheeyappara Waterfalls stop'],
                    ['time' => 'Evening',    'text' => 'Munnar hotel check-in and dinner'],
                ],
            ],
            [
                'title'       => 'Explore the Nature of Munnar',
                'location'    => 'Munnar',
                'description' => 'Scenic drive with stops at Mattupetty Dam, Kundala Lake, Photo Point and Eco Point. Tea plantation tours. Optional afternoon at Eravikulam National Park (closed Feb–Mar).',
                'activities'  => [
                    ['time' => 'Morning',   'text' => 'Mattupetty Dam and Kundala Lake'],
                    ['time' => 'Midday',    'text' => 'Photo Point & Eco Point'],
                    ['time' => 'Afternoon', 'text' => 'Tea plantation walk'],
                    ['time' => 'Optional',  'text' => 'Eravikulam National Park'],
                ],
            ],
            [
                'title'       => 'Let\'s Go to Thekkady',
                'location'    => 'Munnar → Thekkady',
                'description' => 'Check out and 3–4 hour scenic drive to Thekkady. Optional boat ride at Periyar Lake for wildlife viewing in the late afternoon.',
                'activities'  => [
                    ['time' => 'Morning',   'text' => 'Drive Munnar to Thekkady'],
                    ['time' => 'Afternoon', 'text' => 'Optional Periyar Lake boat ride'],
                    ['time' => 'Evening',   'text' => 'Thekkady hotel check-in'],
                ],
            ],
            [
                'title'       => 'Sail into Serenity — Alleppey Houseboat',
                'location'    => 'Alleppey Backwaters',
                'description' => 'Drive 4–5 hours to Alleppey. Board your private houseboat for a backwater cruise. All meals on board. Overnight on the boat.',
                'activities'  => [
                    ['time' => 'Morning',   'text' => 'Drive to Alleppey'],
                    ['time' => 'Noon',      'text' => 'Board private houseboat — lunch on board'],
                    ['time' => 'Evening',   'text' => 'Sunset cruise and dinner on board'],
                ],
            ],
            [
                'title'       => 'Farewell Kerala',
                'location'    => 'Alleppey → Cochin',
                'description' => 'Breakfast on the houseboat. Transfer to Cochin Railway Station or Airport for your onward journey.',
                'activities'  => [
                    ['time' => 'Morning', 'text' => 'Breakfast and houseboat checkout'],
                    ['time' => 'Onward',  'text' => 'Transfer to Cochin'],
                ],
            ],
        ]);

        // ─────────────────────────────────────────────────────────────
        // 5. GROOVY MANALI KASOL PACKAGE
        // ─────────────────────────────────────────────────────────────
        $groovyManali = Package::create([
            'title'            => 'Groovy Manali Kasol Package',
            'slug'             => 'groovy-manali-kasol-package',
            'location'         => 'Manali & Kasol',
            'country'          => 'India',
            'category'         => 'adventure',
            'badge'            => 'none',
            'days'             => 5,
            'nights'           => 4,
            'tier'             => 'standard',
            'price_per_person' => 16499,
            'rating'           => 4.6,
            'review_count'     => 58,
            'description'      => 'Five days of Himalayan vibes — Manali sights, Solang adventure and a Kasol camp.',
            'overview'         => 'A five-day mountain retreat combining Manali\'s temples and Mall Road with Solang Valley\'s adventure sports and a riverside camp night in Kasol. Visit Manikaran Sahib Gurudwara and the Shiv Temple before heading back.',
            'highlights'       => [
                ['title' => 'Solang Valley',          'description' => 'Snow-capped peaks with optional ropeway, zorbing and paragliding.'],
                ['title' => 'Hadimba Devi Temple',    'description' => 'Wood-carved cedar temple in the deodar forest at Manali.'],
                ['title' => 'Kasol Riverside Camp',   'description' => 'A night at a Parvati riverside camp with bonfire vibes.'],
                ['title' => 'Manikaran Sahib',        'description' => 'Hot springs and Gurudwara at the holy Manikaran shrine.'],
            ],
            'inclusions'       => [
                '4 nights accommodation on double-sharing basis',
                '3 nights Manali hotel + 1 night Kasol camp',
                '4 breakfasts and 4 dinners',
                'Private vehicle for transfers and sightseeing',
                'Airport / railway station pickup and drop',
                'Driver, tolls, fuel and 5% GST',
            ],
            'exclusions'       => [
                'Train and flight fares',
                'Meals not listed in itinerary',
                'Adventure activities (paid separately at destination)',
                'Personal shopping',
                'Honeymoon kit (₹2,000 extra)',
                'Disruptions beyond company control',
            ],
            'includes_icons'   => ['hotel', 'meals', 'transfers'],
            // Manali / Kasol — snowy peaks and Parvati valley
            'hero_image'       => 'https://images.unsplash.com/photo-1597074866923-dc0589150358?w=1600&q=85',
            'gallery_images'   => [
                'https://images.unsplash.com/photo-1626621341517-bbf3d9990a23?w=900&q=85',
                'https://images.unsplash.com/photo-1580188897697-6ab9d3e75e3e?w=900&q=85',
                'https://images.unsplash.com/photo-1605649487212-47bdab064df7?w=900&q=85',
                'https://images.unsplash.com/photo-1508808787381-723dab9f9e8b?w=900&q=85',
            ],
            'is_featured'      => false,
            'is_active'        => true,
            'sort_order'       => 5,
            'seats_left'       => null,
        ]);

        $this->createDays($groovyManali->id, [
            [
                'title'       => 'Welcome — Delhi to Manali',
                'location'    => 'Delhi → Manali',
                'description' => 'Airport / station pickup at Delhi. 305 km / 11–12 hour overnight drive to Manali. Hotel check-in and rest.',
                'activities'  => [
                    ['time' => 'On arrival', 'text' => 'Delhi airport / station pickup'],
                    ['time' => 'Day & night', 'text' => 'Drive to Manali (11–12 hrs)'],
                    ['time' => 'Evening',     'text' => 'Hotel check-in and dinner'],
                ],
            ],
            [
                'title'       => 'Manali Local',
                'location'    => 'Manali',
                'description' => 'Breakfast with mountain views. Sightseeing covers Hadimba Devi Temple, Nehru Kund, Van Vihar, Buddhist Monastery and Mall Road.',
                'activities'  => [
                    ['time' => 'Morning',   'text' => 'Breakfast and Hadimba Devi Temple'],
                    ['time' => 'Midday',    'text' => 'Nehru Kund & Van Vihar'],
                    ['time' => 'Afternoon', 'text' => 'Buddhist Monastery'],
                    ['time' => 'Evening',   'text' => 'Mall Road shopping and dinner'],
                ],
            ],
            [
                'title'       => 'Solang Valley',
                'location'    => 'Solang Valley',
                'description' => 'Visit Solang Valley with snow-capped mountain views. Optional ropeway, zorbing and paragliding. Atal Tunnel and Rohtang Pass available at extra cost.',
                'activities'  => [
                    ['time' => 'Morning',   'text' => 'Drive to Solang Valley'],
                    ['time' => 'Day',       'text' => 'Optional ropeway, zorbing, paragliding'],
                    ['time' => 'Optional',  'text' => 'Atal Tunnel & Rohtang Pass'],
                    ['time' => 'Evening',   'text' => 'Return to hotel and dinner'],
                ],
            ],
            [
                'title'       => 'Transfer to Kasol',
                'location'    => 'Manali → Kullu → Kasol',
                'description' => 'Travel via Kullu (2–3 hours). Optional river rafting and adventure activities. Visit Manikaran Sahib Gurudwara, Manikaran Shiv Temple and Kasol Market. Overnight at Kasol camp.',
                'activities'  => [
                    ['time' => 'Morning',   'text' => 'Drive to Kasol via Kullu'],
                    ['time' => 'Optional',  'text' => 'River rafting and adventure activities'],
                    ['time' => 'Afternoon', 'text' => 'Manikaran Sahib Gurudwara and Shiv Temple'],
                    ['time' => 'Evening',   'text' => 'Kasol Market and overnight at camp'],
                ],
            ],
            [
                'title'       => 'Return Home',
                'location'    => 'Kasol → Delhi',
                'description' => 'Breakfast and check-out. 9–10 hour transfer back to Delhi Railway Station for your onward journey.',
                'activities'  => [
                    ['time' => 'Morning', 'text' => 'Breakfast and check-out'],
                    ['time' => 'Day',     'text' => 'Drive Kasol → Delhi (9–10 hrs)'],
                ],
            ],
        ]);

        // ─────────────────────────────────────────────────────────────
        // 6. LETS GO MANALI VOLVO PACKAGE
        // ─────────────────────────────────────────────────────────────
        $manaliVolvo = Package::create([
            'title'            => 'Lets Go Manali — Volvo Package',
            'slug'             => 'lets-go-manali-volvo-package',
            'location'         => 'Manali',
            'country'          => 'India',
            'category'         => 'honeymoon',
            'badge'            => 'none',
            'days'             => 6,
            'nights'           => 5,
            'tier'             => 'standard',
            'price_per_person' => 17999,
            'rating'           => 4.6,
            'review_count'     => 64,
            'description'      => 'Budget honeymoon to Manali — Volvo bus from Delhi, mountain hotel, honeymoon kit included.',
            'overview'         => 'A budget-friendly honeymoon getaway to the Himachal hills with comfortable Volvo semi-sleeper transport from Delhi. Stay in mountain-view rooms in Manali, explore Solang Valley, Atal Tunnel & Rohtang Pass, Kasol & Manikaran. Includes a honeymoon kit with candle-light dinner, bed decoration and cake.',
            'highlights'       => [
                ['title' => 'Honeymoon Kit',           'description' => 'Candle-light dinner, room decoration and cake on arrival.'],
                ['title' => 'Volvo Bus from Delhi',    'description' => 'Comfortable semi-sleeper Volvo both ways — no driving stress.'],
                ['title' => 'Solang & Rohtang',        'description' => 'Snow point with adventure sports plus Atal Tunnel & Rohtang Pass.'],
                ['title' => 'Manikaran & Vaishnodevi', 'description' => 'Sacred Gurudwara and temple visits in the Parvati valley.'],
            ],
            'inclusions'       => [
                'Double-occupancy accommodation (3 nights Manali)',
                'Return Volvo bus tickets (Delhi ⇄ Manali)',
                '3 breakfasts and 3 dinners',
                'Full-day sightseeing tours by private car',
                'Honeymoon kit (candle-light dinner, decoration, cake)',
                'Driver, fuel, tolls and GST',
            ],
            'exclusions'       => [
                'Train / air fare to Delhi',
                'Meals beyond package inclusions',
                'Adventure sports and shopping expenses',
                'Disruptions beyond company control',
            ],
            'includes_icons'   => ['hotel', 'meals', 'transfers'],
            // Manali Volvo — winding mountain road approach
            'hero_image'       => 'https://images.unsplash.com/photo-1605649487212-47bdab064df7?w=1600&q=85',
            'gallery_images'   => [
                'https://images.unsplash.com/photo-1626621341517-bbf3d9990a23?w=900&q=85',
                'https://images.unsplash.com/photo-1597074866923-dc0589150358?w=900&q=85',
                'https://images.unsplash.com/photo-1580188897697-6ab9d3e75e3e?w=900&q=85',
                'https://images.unsplash.com/photo-1508808787381-723dab9f9e8b?w=900&q=85',
            ],
            'is_featured'      => false,
            'is_active'        => true,
            'sort_order'       => 6,
            'seats_left'       => null,
        ]);

        $this->createDays($manaliVolvo->id, [
            [
                'title'       => 'Depart Delhi by Volvo',
                'location'    => 'Delhi → Manali',
                'description' => 'Reach the Volvo boarding point in Delhi by evening. Board the Volvo Semi-sleeper bus for the overnight journey to Manali.',
                'activities'  => [
                    ['time' => 'Evening',  'text' => 'Reporting at Volvo boarding point in Delhi'],
                    ['time' => 'Night',    'text' => 'Overnight Volvo journey to Manali'],
                ],
            ],
            [
                'title'       => 'Arrive Manali',
                'location'    => 'Manali',
                'description' => 'Arrive Manali by 8–9 AM. Hotel check-in. Day at leisure — shop and explore Mall Road on your own.',
                'activities'  => [
                    ['time' => '08:00–09:00', 'text' => 'Arrive Manali and hotel check-in'],
                    ['time' => 'Day',          'text' => 'Leisure / Mall Road on your own'],
                    ['time' => 'Evening',      'text' => 'Honeymoon kit setup and dinner'],
                ],
            ],
            [
                'title'       => 'Solang Valley & Rohtang',
                'location'    => 'Solang Valley → Atal Tunnel → Rohtang Pass',
                'description' => 'Breakfast and full-day sightseeing at Solang Valley snow point with adventure sports. Visit Atal Tunnel and Rohtang Pass.',
                'activities'  => [
                    ['time' => 'Morning',   'text' => 'Breakfast and drive to Solang Valley'],
                    ['time' => 'Midday',    'text' => 'Snow point and adventure sports'],
                    ['time' => 'Afternoon', 'text' => 'Atal Tunnel and Rohtang Pass'],
                    ['time' => 'Evening',   'text' => 'Return to hotel and dinner'],
                ],
            ],
            [
                'title'       => 'Kasol via Kullu',
                'location'    => 'Kullu → Kasol',
                'description' => 'Breakfast and travel to Kasol via Kullu. Optional river rafting. Visit a shawl factory. See Manikaran Sahib Gurudwara and Vaishnodevi Temple. Return to Manali.',
                'activities'  => [
                    ['time' => 'Morning',   'text' => 'Breakfast and drive towards Kasol'],
                    ['time' => 'Optional',  'text' => 'River rafting at Kullu'],
                    ['time' => 'Midday',    'text' => 'Shawl factory visit'],
                    ['time' => 'Afternoon', 'text' => 'Manikaran Sahib Gurudwara & Vaishnodevi Temple'],
                    ['time' => 'Evening',   'text' => 'Return to Manali hotel'],
                ],
            ],
            [
                'title'       => 'Manali Local & Departure',
                'location'    => 'Manali → Delhi',
                'description' => 'Breakfast and check-out. Local sightseeing — Hadimba Devi Temple, Club House, Van Vihar, Tibetan Monastery and Vashisht Sulphur Spring. Board Volvo Bus to Delhi in the evening.',
                'activities'  => [
                    ['time' => 'Morning',   'text' => 'Breakfast and check-out'],
                    ['time' => 'Day',       'text' => 'Hadimba Devi, Club House, Van Vihar, Tibetan Monastery, Vashisht Sulphur Spring'],
                    ['time' => 'Evening',   'text' => 'Board Volvo Bus to Delhi'],
                ],
            ],
            [
                'title'       => 'Arrive Delhi',
                'location'    => 'Delhi',
                'description' => 'Arrive Delhi by 10 AM. Tour concludes here.',
                'activities'  => [
                    ['time' => '~10:00', 'text' => 'Arrive Delhi — tour concludes'],
                ],
            ],
        ]);
    }

    /**
     * Bulk-create PackageDay records with auto-incrementing day_number.
     */
    private function createDays(int $packageId, array $days): void
    {
        foreach ($days as $i => $day) {
            PackageDay::create([
                'package_id'  => $packageId,
                'day_number'  => $i + 1,
                'title'       => $day['title'],
                'location'    => $day['location'] ?? null,
                'description' => $day['description'] ?? null,
                'activities'  => $day['activities'] ?? [],
            ]);
        }
    }
}
