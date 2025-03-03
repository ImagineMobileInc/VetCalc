<?php
// index.php - Homepage
require_once 'includes/config.php';

// Get featured calculators
function getFeaturedCalculators() {
    $conn = connectDB();
    
    if (!$conn) {
        return [
            ['id' => 1, 'name' => 'Emergency Drugs', 'description' => 'Quick calculations for critical care situations', 'url' => 'emergency-drugs.php', 'icon' => 'ambulance'],
            ['id' => 9, 'name' => 'Chocolate Toxicity', 'description' => 'Calculate chocolate toxicity risks', 'url' => 'chocolate-toxicity.php', 'icon' => 'candy-cane'],
            ['id' => 6, 'name' => 'Fluid Therapy', 'description' => 'Fluid therapy calculations', 'url' => 'fluid-therapy.php', 'icon' => 'tint'],
            ['id' => 4, 'name' => 'Anesthetics', 'description' => 'Dosage calculations for anesthetic protocols', 'url' => 'anesthetics.php', 'icon' => 'procedures'],
            ['id' => 11, 'name' => 'Feline Grimace Scale', 'description' => 'Pain assessment for cats', 'url' => 'feline-grimace.php', 'icon' => 'cat'],
            ['id' => 16, 'name' => 'Calorie Requirements', 'description' => 'Calculate nutritional needs', 'url' => 'calorie-requirements.php', 'icon' => 'utensils']
        ];
    }
    
    $query = "SELECT * FROM calculators WHERE featured = 1 ORDER BY name LIMIT 6";
    $result = $conn->query($query);
    
    $calculators = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $calculators[] = $row;
        }
    }
    
    $conn->close();
    return $calculators;
}

// Get featured references
function getFeaturedReferences() {
    $conn = connectDB();
    
    if (!$conn) {
        return [
            ['id' => 1, 'name' => 'RECOVER Guidelines', 'description' => 'CPR & emergency procedures', 'url' => 'references/recover-guidelines.php', 'icon' => 'ambulance'],
            ['id' => 2, 'name' => 'Normal Lab Values', 'description' => 'Reference ranges for lab tests', 'url' => 'references/lab-values.php', 'icon' => 'vial'],
            ['id' => 3, 'name' => 'AAHA Vaccination Guidelines', 'description' => 'Updated vaccination protocols', 'url' => 'references/vaccination-guidelines.php', 'icon' => 'syringe'],
            ['id' => 4, 'name' => 'Poisonous Plant Lookup', 'description' => 'Identify toxic plants', 'url' => 'references/poisonous-plants.php', 'icon' => 'leaf']
        ];
    }
    
    $query = "SELECT * FROM references WHERE featured = 1 ORDER BY name LIMIT 4";
    $result = $conn->query($query);
    
    $references = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $references[] = $row;
        }
    }
    
    $conn->close();
    return $references;
}

// Get popular drug searches
function getPopularDrugs() {
    $conn = connectDB();
    
    if (!$conn) {
        return [
            'Metronidazole', 'Meloxicam', 'Dexmedetomidine', 'Gabapentin', 'Cerenia', 
            'Convenia', 'Epinephrine', 'Apoquel', 'Atropine', 'Midazolam'
        ];
    }
    
    $query = "SELECT name FROM drugs ORDER BY search_count DESC LIMIT 10";
    $result = $conn->query($query);
    
    $drugs = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $drugs[] = $row['name'];
        }
    }
    
    $conn->close();
    return $drugs;
}

// Get recent patients (would come from session or user data in real app)
function getRecentPatients() {
    return [
        ['id' => 1, 'name' => 'Buddy', 'species' => 'Canine', 'breed' => 'Labrador Retriever', 'age' => '5 years', 'weight' => '28 kg'],
        ['id' => 2, 'name' => 'Mittens', 'species' => 'Feline', 'breed' => 'Domestic Shorthair', 'age' => '10 years', 'weight' => '4.2 kg'],
        ['id' => 3, 'name' => 'Rex', 'species' => 'Canine', 'breed' => 'German Shepherd', 'age' => '8 years', 'weight' => '34 kg']
    ];
}

// Get data for page
$featuredCalculators = getFeaturedCalculators();
$featuredReferences = getFeaturedReferences();
$popularDrugs = getPopularDrugs();
$recentPatients = getRecentPatients();

// Get the page header
echo getHeader('VetCalcPro - Veterinary Calculators');
?>

<!-- Hero section -->
<section class="hero">
    <div class="container">
        <h1>Veterinary Calculators Made Easy</h1>
        <p>Comprehensive tools for veterinary professionals. Quick calculations, reference guides, and patient management in one place.</p>
        
        <!-- Search bar with drug search and autocomplete -->
        <div class="search-bar">
            <form action="search-results.php" method="get" id="main-search-form">
                <input type="text" name="q" id="search-input" placeholder="Search for a drug, calculator, or reference...">
                <button type="submit"><i class="fas fa-search"></i> Search</button>
            </form>
            
            <!-- Search suggestions will appear here -->
            <div class="search-suggestions" id="search-suggestions"></div>
        </div>
        
        <!-- Popular searches -->
        <div class="popular-searches">
            <span>Popular searches:</span>
            <?php foreach ($popularDrugs as $index => $drug): ?>
                <?php if ($index < 5): ?>
                <a href="search-results.php?q=<?php echo urlencode($drug); ?>"><?php echo $drug; ?></a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Main content area -->
<main>
    <div class="container">
        <!-- Featured calculators section -->
        <section class="featured-calculators">
            <div class="section-header">
                <h2>Featured Calculators</h2>
                <a href="calculators.php" class="view-all">View All Calculators <i class="fas fa-arrow-right"></i></a>
            </div>
            
            <div class="calculator-grid">
                <?php foreach ($featuredCalculators as $calculator): ?>
                <a href="<?php echo $calculator['url']; ?>" class="calculator-item">
                    <i class="fas fa-<?php echo $calculator['icon']; ?>"></i>
                    <h3><?php echo $calculator['name']; ?></h3>
                    <p><?php echo $calculator['description']; ?></p>
                </a>
                <?php endforeach; ?>
            </div>
        </section>
        
        <!-- Quick access section (two columns: References and Recent Patients) -->
        <section class="quick-access">
            <div class="quick-access-row">
                <!-- References column -->
                <div class="quick-access-column">
                    <div class="section-header">
                        <h2>Veterinary References</h2>
                        <a href="references.php" class="view-all">View All <i class="fas fa-arrow-right"></i></a>
                    </div>
                    
                    <div class="reference-list">
                        <?php foreach ($featuredReferences as $reference): ?>
                        <a href="<?php echo $reference['url']; ?>" class="reference-item">
                            <div class="reference-icon">
                                <i class="fas fa-<?php echo $reference['icon']; ?>"></i>
                            </div>
                            <div class="reference-info">
                                <h3><?php echo $reference['name']; ?></h3>
                                <p><?php echo $reference['description']; ?></p>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Recent patients column -->
                <div class="quick-access-column">
                    <div class="section-header">
                        <h2>Recent Patients</h2>
                        <a href="patients.php" class="view-all">Manage Patients <i class="fas fa-arrow-right"></i></a>
                    </div>
                    
                    <div class="patient-list">
                        <?php foreach ($recentPatients as $patient): ?>
                        <a href="patient-details.php?id=<?php echo $patient['id']; ?>" class="patient-item">
                            <div class="patient-icon">
                                <i class="fas fa-<?php echo strtolower($patient['species']) === 'canine' ? 'dog' : 'cat'; ?>"></i>
                            </div>
                            <div class="patient-info">
                                <h3><?php echo $patient['name']; ?></h3>
                                <p><?php echo $patient['species']; ?> • <?php echo $patient['breed']; ?></p>
                                <p><?php echo $patient['age']; ?> • <?php echo $patient['weight']; ?></p>
                            </div>
                        </a>
                        <?php endforeach; ?>
                        
                        <a href="add-patient.php" class="add-patient">
                            <i class="fas fa-plus"></i> Add New Patient
                        </a>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Calculator categories section -->
        <section class="calculator-categories">
            <div class="section-header">
                <h2>Calculator Categories</h2>
            </div>
            
            <div class="categories-grid">
                <a href="calculators.php?category=1" class="category-card">
                    <i class="fas fa-ambulance"></i>
                    <h3>Emergency</h3>
                    <p>Critical care calculations</p>
                </a>
                <a href="calculators.php?category=2" class="category-card">
                    <i class="fas fa-procedures"></i>
                    <h3>Anesthesia</h3>
                    <p>Anesthetic protocols & CRIs</p>
                </a>
                <a href="calculators.php?category=3" class="category-card">
                    <i class="fas fa-tint"></i>
                    <h3>Fluids</h3>
                    <p>Fluid therapy calculations</p>
                </a>
                <a href="calculators.php?category=4" class="category-card">
                    <i class="fas fa-capsules"></i>
                    <h3>Antibiotics</h3>
                    <p>Antibiotic dosing</p>
                </a>
                <a href="calculators.php?category=5" class="category-card">
                    <i class="fas fa-skull-crossbones"></i>
                    <h3>Toxicology</h3>
                    <p>Toxicity calculators</p>
                </a>
                <a href="calculators.php?category=6" class="category-card">
                    <i class="fas fa-clipboard-list"></i>
                    <h3>Assessment</h3>
                    <p>Clinical scoring tools</p>
                </a>
                <a href="calculators.php?category=7" class="category-card">
                    <i class="fas fa-utensils"></i>
                    <h3>Nutrition</h3>
                    <p>Dietary calculations</p>
                </a>
                <a href="calculators.php?category=8" class="category-card">
                    <i class="fas fa-exchange-alt"></i>
                    <h3>Conversion</h3>
                    <p>Unit converters</p>
                </a>
            </div>
        </section>
        
        <!-- Call to action section -->
        <section class="cta">
            <h2>Streamline Your Veterinary Practice</h2>
            <p>Access all your essential veterinary tools in one place. Save time with quick calculations and comprehensive references.</p>
            <div class="cta-buttons">
                <a href="settings.php" class="btn primary">Customize Settings</a>
                <a href="#" class="btn secondary" id="show-tour">Take a Tour</a>
            </div>
        </section>
    </div>
</main>

<!-- Floating toolbar -->
<div class="toolbar">
    <div class="floating-btn" id="toolbar-toggle">
        <i class="fas fa-plus"></i>
    </div>
    <div class="toolbar-menu" id="toolbar-menu">
        <a href="quick-calc.php"><i class="fas fa-calculator"></i> Quick Calculator</a>
        <a href="scan.php"><i class="fas fa-qrcode"></i> Scan Document/QR</a>
        <a href="add-patient.php"><i class="fas fa-user-plus"></i> Add Patient</a>
        <a href="emergency-drugs.php"><i class="fas fa-ambulance"></i> Emergency Drugs</a>
    </div>
</div>

<!-- Numeric calculator modal (hidden by default) -->
<div class="modal" id="calculator-modal">
    <div class="modal-content">
        <span class="close-modal" id="close-calculator">&times;</span>
        <h2>Calculator</h2>
        <div class="numeric-calculator">
            <div class="calc-display">
                <input type="text" id="calc-input" readonly>
            </div>
            <div class="calc-buttons">
                <button class="calc-btn">7</button>
                <button class="calc-btn">8</button>
                <button class="calc-btn">9</button>
                <button class="calc-btn operator">÷</button>
                <button class="calc-btn">4</button>
                <button class="calc-btn">5</button>
                <button class="calc-btn">6</button>
                <button class="calc-btn operator">×</button>
                <button class="calc-btn">1</button>
                <button class="calc-btn">2</button>
                <button class="calc-btn">3</button>
                <button class="calc-btn operator">-</button>
                <button class="calc-btn">0</button>
                <button class="calc-btn">.</button>
                <button class="calc-btn clear">C</button>
                <button class="calc-btn operator">+</button>
                <button class="calc-btn equals">=</button>
            </div>
        </div>
    </div>
</div>

<!-- Drug quick view modal (hidden by default) -->
<div class="modal" id="drug-modal">
    <div class="modal-content">
        <span class="close-modal" id="close-drug">&times;</span>
        <div id="drug-details">
            <!-- Drug details will be loaded here dynamically -->
        </div>
    </div>
</div>

<script>
// JavaScript for interactive functionality
document.addEventListener('DOMContentLoaded', function() {
    // Toolbar toggle
    const toolbarToggle = document.getElementById('toolbar-toggle');
    const toolbarMenu = document.getElementById('toolbar-menu');
    
    toolbarToggle.addEventListener('click', function() {
        toolbarMenu.classList.toggle('active');
    });
    
    // Close toolbar when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.toolbar') && toolbarMenu.classList.contains('active')) {
            toolbarMenu.classList.remove('active');
        }
    });
    
    // Quick calculator modal
    const calculatorModal = document.getElementById('calculator-modal');
    const closeCalculator = document.getElementById('close-calculator');
    
    // Add event listener to quick calculator button in toolbar
    document.querySelector('.toolbar-menu a[href="quick-calc.php"]').addEventListener('click', function(e) {
        e.preventDefault();
        calculatorModal.style.display = 'flex';
    });
    
    closeCalculator.addEventListener('click', function() {
        calculatorModal.style.display = 'none';
    });
    
    // Search functionality with autocomplete
    const searchInput = document.getElementById('search-input');
    const searchSuggestions = document.getElementById('search-suggestions');
    
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        
        if (query.length < 2) {
            searchSuggestions.innerHTML = '';
            searchSuggestions.style.display = 'none';
            return;
        }
        
        // Mock autocomplete - in real app would use AJAX
        const allDrugs = <?php echo json_encode($popularDrugs); ?>;
        const filteredResults = allDrugs.filter(drug => 
            drug.toLowerCase().includes(query.toLowerCase())
        );
        
        if (filteredResults.length > 0) {
            searchSuggestions.innerHTML = '';
            filteredResults.forEach(result => {
                const div = document.createElement('div');
                div.classList.add('suggestion-item');
                div.textContent = result;
                div.addEventListener('click', function() {
                    searchInput.value = result;
                    searchSuggestions.style.display = 'none';
                    // Show drug quick view
                    showDrugQuickView(result);
                });
                searchSuggestions.appendChild(div);
            });
            searchSuggestions.style.display = 'block';
        } else {
            searchSuggestions.innerHTML = '';
            searchSuggestions.style.display = 'none';
        }
    });
    
    // Hide suggestions when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.search-bar')) {
            searchSuggestions.style.display = 'none';
        }
    });
    
    // Drug quick view functionality
    const drugModal = document.getElementById('drug-modal');
    const closeDrug = document.getElementById('close-drug');
    const drugDetails = document.getElementById('drug-details');
    
    function showDrugQuickView(drugName) {
        // In a real app, would fetch drug details via AJAX
        drugDetails.innerHTML = `
            <h2>${drugName}</h2>
            <div class="drug-info">
                <div class="drug-section">
                    <h3>Dosage Information</h3>
                    <p><strong>Canine:</strong> 10-20 mg/kg q12h</p>
                    <p><strong>Feline:</strong> 5-10 mg/kg q12h</p>
                </div>
                <div class="drug-section">
                    <h3>Administration</h3>
                    <p>PO, IV, IM</p>
                    <p>With food to reduce GI upset</p>
                </div>
                <div class="drug-section">
                    <h3>Precautions</h3>
                    <p>Use with caution in patients with liver disease</p>
                    <p>Monitor for vomiting and diarrhea</p>
                </div>
            </div>
            <div class="drug-actions">
                <a href="calculator.php?drug=${encodeURIComponent(drugName)}" class="btn primary">Calculate Dose</a>
                <a href="drug-info.php?drug=${encodeURIComponent(drugName)}" class="btn secondary">Full Information</a>
            </div>
        `;
        
        drugModal.style.display = 'flex';
    }
    
    closeDrug.addEventListener('click', function() {
        drugModal.style.display = 'none';
    });
    
    // Close modals when clicking outside the content
    window.addEventListener('click', function(event) {
        if (event.target === calculatorModal) {
            calculatorModal.style.display = 'none';
        }
        if (event.target === drugModal) {
            drugModal.style.display = 'none';
        }
    });
    
    // Simple numeric calculator functionality
    const calcInput = document.getElementById('calc-input');
    const calcButtons = document.querySelectorAll('.calc-btn');
    
    let calculation = '';
    
    calcButtons.forEach(button => {
        button.addEventListener('click', function() {
            const value = this.textContent;
            
            if (value === 'C') {
                calculation = '';
                calcInput.value = '';
            } else if (value === '=') {
                try {
                    // Replace × and ÷ with * and / for JavaScript eval
                    const calculationForEval = calculation.replace(/×/g, '*').replace(/÷/g, '/');
                    calculation = eval(calculationForEval).toString();
                    calcInput.value = calculation;
                } catch (error) {
                    calcInput.value = 'Error';
                    calculation = '';
                }
            } else {
                calculation += value;
                calcInput.value = calculation;
            }
        });
    });
});
</script>

<?php
// Get the page footer
echo getFooter();
?>
