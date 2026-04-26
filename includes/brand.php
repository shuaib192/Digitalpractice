<?php
/**
 * Digital Practice Ltd - Official Brand Knowledge File
 * Source of truth for all company content, messaging, and identity.
 * Used across the platform for consistent, accurate brand representation.
 */

// =========================================================================
// COMPANY IDENTITY
// =========================================================================
define('BRAND_FULL_NAME',       'The Digital Practice Ltd');
define('BRAND_SHORT_NAME',      'Digital Practice');
define('BRAND_TAGLINE',         'Enable. Build. Scale.');
define('BRAND_EMAIL_GENERAL',   'info@digitalpractice.org');
define('BRAND_EMAIL_SUPPORT',   '247@digitalpractice.org');
define('BRAND_DOMAIN',          'digitalpractice.org');

// =========================================================================
// ABOUT THE COMPANY
// =========================================================================
define('BRAND_ABOUT_INTRO',
    'Resolving all forms of Digital Skilling, market access, and branding-related challenges by offering world-class, industry-standard services through hands-on training, service delivery, and client-focused solutions. ' .
    'Our Practice has a long history of outstanding performance, with a multiplier effect through our seasoned experts and customer-centric services. ' .
    'We uphold all forms of industry standards while delivering services to our clients. ' .
    'Our products are designed with accessibility in mind, incorporating critical features that adopt the Leave No One Behind value, allowing us to cater for all and sundry, inclusive of persons with disabilities and other vulnerable groups.'
);

define('BRAND_ABOUT_SHORT',
    'The Digital Practice Ltd is a digital solutions and capacity-building company committed to ' .
    'helping individuals, businesses, and organizations thrive in an increasingly digital world. ' .
    'We exist to bridge the gap between access and impact by equipping people with practical ' .
    'digital skills, tools, and systems that enable growth, efficiency, and long-term relevance.'
);

define('BRAND_ABOUT_FULL',
    BRAND_ABOUT_SHORT . "\n\n" .
    'Our work sits at the intersection of technology, skills development, and digital transformation. ' .
    'Through training, advisory services, and the deployment of practical digital solutions, we support ' .
    'our clients in adopting digital practices that are not only current but also sustainable and inclusive.' . "\n\n" .
    'At Digital Practice, we believe that digital progress should be practical, accessible, and ' .
    'purposeful — driven by real needs and measurable outcomes.'
);

// =========================================================================
// MISSION & VISION
// =========================================================================
define('BRAND_VISION',
    'To be a trusted driver of inclusive digital growth, empowering people and organizations to succeed ' .
    'in the digital economy through practical skills and effective digital solutions.'
);

define('BRAND_MISSION',
    'To equip individuals, businesses, and institutions with the knowledge, tools, and digital capabilities ' .
    'they need to operate efficiently, grow sustainably, and create meaningful impact in a technology-driven world.'
);

// =========================================================================
// COMPANY OBJECTS (from incorporation)
// =========================================================================
$BRAND_OBJECTS = [
    'To provide digital access training, skill development programmes, and capacity-building in digital literacy and related fields.',
    'To offer digital access support, digital enterprise development programmes, and capacity-building activities that enable individuals and businesses to leverage digital tools for growth.',
    'To carry out digital marketing services including content creation, online promotion, social media management, and related digital engagement activities.',
    'To engage in the supply of digital tools, devices, accessories, and equipment required for digital operations.',
    'To undertake installation and basic support services related to digital equipment and tools.',
    'To undertake all lawful activities incidental or conducive to the attainment of the above objects.',
];

// =========================================================================
// VALUES — ALIGN FRAMEWORK
// =========================================================================
$BRAND_VALUES = [
    [
        'letter'    => 'A',
        'title'     => 'Access with Purpose',
        'tagline'   => 'Purposeful access.',
        'supports'  => 'Enable',
        'short'     => 'We expand digital access intentionally, ensuring technology creates real opportunity and meaningful participation.',
        'policy'    => 'We ensure that everyone who can benefit from our services has fair and meaningful access to digital tools, skills, and opportunities.',
    ],
    [
        'letter'    => 'L',
        'title'     => 'Learning that Lasts',
        'tagline'   => 'Enduring learning.',
        'supports'  => 'Build',
        'short'     => 'We prioritize skills, systems, and knowledge that endure beyond delivery and empower independence.',
        'policy'    => 'We focus on building real skills and knowledge that people and organizations can use long after our programmes or projects end.',
    ],
    [
        'letter'    => 'I',
        'title'     => 'Impact over Activity',
        'tagline'   => 'Outcome-driven work.',
        'supports'  => null,
        'short'     => 'We focus on outcomes that can be measured, felt, and sustained — not output for output\'s sake.',
        'policy'    => 'We measure our success by real, measurable results — not by how much we do or produce.',
    ],
    [
        'letter'    => 'G',
        'title'     => 'Growth by Design',
        'tagline'   => 'Designed growth.',
        'supports'  => 'Scale',
        'short'     => 'We plan for scale from the start, building solutions that are adaptable, repeatable, and resilient.',
        'policy'    => 'We design services and systems to be adaptable, scalable, and sustainable over time.',
    ],
    [
        'letter'    => 'N',
        'title'     => 'Neutrality & Integrity',
        'tagline'   => 'Ethical integrity.',
        'supports'  => null,
        'short'     => 'We apply technology ethically, responsibly, and without bias, maintaining trust in every engagement.',
        'policy'    => 'We act ethically, transparently, and without bias in all our work, partnerships, and decision-making.',
    ],
];
define('BRAND_VALUES_SUMMARY', 'Access fairly. Build skills. Deliver impact. Grow responsibly.');

// =========================================================================
// SERVICES
// =========================================================================
$BRAND_SERVICES = [
    [
        'id'          => 'digital-skills',
        'title'       => 'Digital Skills & Capacity Building',
        'icon'        => 'fa-graduation-cap',
        'summary'     => 'Training, upskilling, and digital literacy programmes for individuals, teams, and communities to build practical, job-ready digital competence.',
        'description' => 'We provide comprehensive digital skills and capacity-building programmes designed to strengthen the ability of individuals, teams, and organizations to function effectively in an increasingly digital environment. Our work in this area goes beyond basic training to focus on confidence, practical application, and long-term capability. Our programmes are structured to address different levels of digital exposure, from foundational digital literacy to intermediate and role-specific competencies. This includes the use of everyday digital tools, online collaboration platforms, productivity software, digital communication, internet safety, and responsible technology use. Where relevant, we also integrate emerging digital topics aligned with workforce readiness and entrepreneurship. We place strong emphasis on context-aware learning. This means recognizing constraints such as limited devices, connectivity challenges, varying educational backgrounds, and reliance on mobile technology. Our delivery methods are flexible, inclusive, and learner-centered, ensuring participants are not only taught but also supported to practice, adapt, and apply what they learn in real-life situations. The objective of this service is to build lasting digital confidence and independence, enabling participants to continue learning, working, and innovating long after formal engagement with our programmes has ended.',
    ],
    [
        'id'          => 'digital-inclusion',
        'title'       => 'Digital Inclusion & Access Support',
        'icon'        => 'fa-universal-access',
        'summary'     => 'Initiatives and support that enable inclusive access to digital tools, platforms, and opportunities, especially for underserved groups and organizations.',
        'description' => 'We design and support digital inclusion initiatives that address systemic barriers preventing individuals and organizations from fully participating in the digital economy. This service is rooted in the belief that access to digital tools and opportunities must be equitable, intentional, and responsive to real-world challenges. Our work in digital inclusion includes improving access to devices, platforms, knowledge, and support systems, as well as embedding accessibility and inclusion considerations into programme and service design. We pay close attention to factors such as affordability, language, disability inclusion, usability, and cultural context. We collaborate with partners, institutions, and community-based organizations to extend reach and ensure that digital solutions are appropriate for the populations they serve. Rather than assuming one-size-fits-all solutions, we prioritize approaches that recognize diverse needs and lived realities. This service ensures that digital transformation efforts do not unintentionally exclude or disadvantage certain groups and that technology becomes a genuine enabler of opportunity, dignity, and participation.',
    ],
    [
        'id'          => 'business-digitisation',
        'title'       => 'Business Digitisation & Digital Transformation',
        'icon'        => 'fa-arrows-rotate',
        'summary'     => 'Helping startups, SMEs, and organizations adopt digital systems, automate processes, and integrate technology to improve efficiency and growth.',
        'description' => 'We support businesses, social enterprises, and organizations to transition from fragmented, manual, or inefficient processes to structured, effective digital systems. Our approach to digitization is practical, phased, and aligned with organizational capacity and growth objectives. This service includes assessing existing workflows, identifying inefficiencies, and recommending appropriate digital tools and systems that improve operations. We support the adoption of productivity tools, digital record-keeping, communication platforms, process automation, and basic data management practices. We prioritize fit-for-purpose solutions, ensuring that technology introduced is usable, maintainable, and aligned with staff skill levels. We also provide guidance and support to ensure teams understand how to use new systems effectively and confidently. The goal is not technology adoption for its own sake but meaningful transformation that improves efficiency, accountability, service delivery, and decision-making while preparing organizations for sustainable growth.',
    ],
    [
        'id'          => 'digital-marketing',
        'title'       => 'Digital Marketing & Online Presence',
        'icon'        => 'fa-bullhorn',
        'summary'     => 'End-to-end digital marketing services including content creation, social media management, online promotion, and visibility optimization.',
        'description' => 'We provide digital marketing and online presence management services that help organisations communicate clearly, engage effectively with their audiences, and grow visibility in the digital space. Our work in this area includes content development, social media management, digital campaign planning, online audience engagement, and optimization of business and organizational profiles on relevant platforms. We approach digital marketing strategically, ensuring activities are aligned with organizational goals rather than driven by trends or volume. We place emphasis on ethical, transparent, and responsible communication, ensuring messaging accurately represents the organization and builds trust with audiences. Success is measured using meaningful indicators such as engagement quality, audience relevance, and conversion outcomes where applicable. This service supports organizations to strengthen their digital presence, tell their story effectively, and expand their reach in a way that supports long-term growth and credibility.',
    ],
    [
        'id'          => 'digital-advisory',
        'title'       => 'Digital Advisory & Strategy',
        'icon'        => 'fa-compass',
        'summary'     => 'Research-driven advisory, digital strategy development, and implementation support for organizations looking to scale or improve digital impact.',
        'description' => 'We provide digital advisory, research, and strategy services to organizations seeking clarity, direction, and informed decision-making in their digital initiatives. This service supports leadership teams, programme managers, and partners navigating digital adoption or scale. Our advisory work includes needs assessments, research, digital readiness analysis, strategy development, and implementation guidance. We help organizations understand where they are, what is feasible, and how best to deploy digital solutions to achieve their objectives. We prioritize evidence-based and context-sensitive recommendations, avoiding abstract or trend-driven advice. Our strategies are practical, realistic, and aligned with organizational capacity, resources, and long-term goals. This service ensures that digital initiatives are intentional, well-planned, and positioned to deliver measurable impact rather than fragmented or unsustainable outcomes.',
    ],
    [
        'id'          => 'digital-tools',
        'title'       => 'Digital Tools & Solutions Supply',
        'icon'        => 'fa-toolbox',
        'summary'     => 'Provision, setup, and support of essential digital tools, platforms, and accessories needed for effective digital operations.',
        'description' => 'We engage in the supply, configuration, and support of digital tools and solutions required for effective digital operations. This includes software platforms, productivity tools, digital business solutions, and supporting accessories where appropriate. Our approach emphasizes suitability, affordability, and ease of adoption. We assess organizational needs before recommending tools, ensuring that solutions align with operational requirements and user capabilities. Beyond supply, we provide setup support, basic configuration, and user guidance to ensure tools are properly integrated and actively used. Where necessary, we also provide ongoing support to address adoption challenges. The objective of this service is to ensure that digital tools genuinely enhance productivity and service delivery, rather than becoming underutilized or misaligned investments.',
    ],
];

// =========================================================================
// PRODUCTS
// =========================================================================
$BRAND_PRODUCTS = [
    [
        'title'       => 'Electronic Invoicing (myinvoice.ng)',
        'slug'        => 'einvoice',
        'url'         => 'https://myinvoice.ng',
        'summary'     => 'Free professional invoicing tool for MSMEs. No cost to users. Revenue model via Google AdSense.',
        'icon'        => 'fa-file-invoice',
    ],
    [
        'title'       => 'Data & Airtime Vending (2suresub.com.ng)',
        'slug'        => 'data-vending',
        'url'         => 'https://2suresub.com.ng',
        'summary'     => 'Affordable data and airtime vending platform for individuals and small businesses.',
        'icon'        => 'fa-wifi',
    ],
    [
        'title'       => 'Games — Sudoku & Mathematical Games',
        'slug'        => 'games',
        'url'         => null,
        'summary'     => 'Educational digital games: Sudoku, mathematical challenges, and brain-training tools designed for learning and engagement.',
        'icon'        => 'fa-gamepad',
    ],
    [
        'title'       => 'MSME & Tech Blog (techgate.blog)',
        'slug'        => 'blog',
        'url'         => 'https://techgate.blog',
        'summary'     => 'A dedicated news, insights, and resources blog for MSMEs and the broader technology community.',
        'icon'        => 'fa-newspaper',
    ],
];

// =========================================================================
// SUBSIDIARIES
// =========================================================================
$BRAND_SUBSIDIARIES = [
    ['name' => '2suresub.com.ng',  'description' => 'Data Vending Platform',       'url' => 'https://2suresub.com.ng'],
    ['name' => 'techgate.blog',    'description' => 'Technology & MSME News Blog',  'url' => 'https://techgate.blog'],
    ['name' => 'myinvoice.ng',     'description' => 'Free Invoicing for MSMEs',     'url' => 'https://myinvoice.ng'],
];

// =========================================================================
// LEADERSHIP
// =========================================================================
$BRAND_LEADERSHIP = [
    ['title' => 'Founder and Chief Executive Officer (CEO)', 'note' => 'Leads the board and executive directors.'],
];

// =========================================================================
// STRATEGIC POSITIONING SUMMARY
// =========================================================================
define('BRAND_POSITIONING',
    'Across all services, The Digital Practice Ltd operates as a digital capability and transformation partner, ' .
    'focused on enabling access, building capacity, and supporting sustainable scale. Our work sits at the ' .
    'intersection of skills, systems, inclusion, and strategy — ensuring that technology delivers real and lasting value.'
);
