<?php
// calculators.php - Main calculator listing page
require_once 'includes/config.php';

// Get calculator categories
function getCalculatorCategories() {
    $conn = connectDB();
    
    if (!$conn) {
        return [
            ['id' => 1, 'name' => 'Emergency', 'icon' => 'ambulance'],
            ['id' => 2, 'name' => 'Anesthesia', 'icon' => 'procedures'],
            ['id' => 3, 'name' => 'Fluids', 'icon' => 'tint'],
            ['id' => 4, 'name' => 'Antibiotics', 'icon' => 'capsules'],
            ['id' => 5, 'name' => 'Toxicology', 'icon' => 'skull-crossbones'],
            ['id' => 6, 'name' => 'Assessment', 'icon' => 'clipboard-list'],
            ['id' => 7, 'name' => 'Nutrition', 'icon' => 'utensils'],
            ['id' => 8, 'name' => 'Conversion', 'icon' => 'exchange-alt']
        ];
    }
    
    $query = "SELECT * FROM calculator_categories ORDER BY name";
    $result = $conn->query($query);
    
    $categories = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
    }
    
    $conn->close();
    return $categories;
}

// Get calculators
function getCalculators() {
    $conn = connectDB();
    
    if (!$conn) {
        return [
            // Emergency
            ['id' => 1, 'name' => 'Emergency Drugs', 'description' => 'Quick calculations for critical care situations', 'category_id' => 1, 'url' => 'emergency-drugs.php', 'icon' => 'ambulance'],
            ['id' => 2, 'name' => 'Trauma Triage', 'description' => 'Assessment tools for trauma patients', 'category_id' => 1, 'url' => 'trauma-triage.php', 'icon' => 'hospital'],
            ['id' => 3, 'name' => 'Blood Transfusions', 'description' => 'Calculate transfusion requirements', 'category_id' => 1, 'url' => 'blood-transfusions.php', 'icon' => 'syringe'],
            
            // Anesthesia
            ['id' => 4, 'name' => 'Anesthetics', 'description' => 'Dosage calculations for anesthetic protocols', 'category_id' => 2, 'url' => 'anesthetics.php', 'icon' => 'procedures'],
            ['id' => 5, 'name' => 'CRI Calculator', 'description' => 'Constant rate infusion calculators', 'category_id' => 2, 'url' => 'cri-calculator.php', 'icon' => 'pump-medical'],
            
            // Fluids
            ['id' => 6, 'name' => 'Fluid Therapy', 'description' => 'Fluid therapy calculations', 'category_id' => 3, 'url' => 'fluid-therapy.php', 'icon' => 'tint'],
            
            // Antibiotics
            ['id' => 7, 'name' => 'Antibiotics', 'description' => 'Antibiotic dosing calculations', 'category_id' => 4, 'url' => 'antibiotics.php', 'icon' => 'capsules'],
            ['id' => 8, 'name' => 'Common Veterinary Drugs', 'description' => 'Quick reference for frequently used medications', 'category_id' => 4, 'url' => 'common-drugs.php', 'icon' => 'pills'],
            
            // Toxicology
            ['id' => 9, 'name' => 'Chocolate Toxicity', 'description' => 'Calculate chocolate toxicity risks', 'category_id' => 5, 'url' => 'chocolate-toxicity.php', 'icon' => 'candy-cane'],
            ['id' => 10, 'name' => 'Rodenticide Toxicity', 'description' => 'Assess exposure and treatment needs', 'category_id' => 5, 'url' => 'rodenticide-toxicity.php', 'icon' => 'skull-crossbones'],
            
            // Assessment
            ['id' => 11, 'name' => 'Feline Grimace Scale', 'description' => 'Pain assessment for cats', 'category_id' => 6, 'url' => 'feline-grimace.php', 'icon' => 'cat'],
            ['id' => 12, 'name' => 'Modified Glasgow Coma Scale', 'description' => 'Neurological assessment tool', 'category_id' => 6, 'url' => 'glasgow-coma.php', 'icon' => 'brain'],
            ['id' => 13, 'name' => 'Blood Pressure', 'description' => 'Track and analyze blood pressure readings', 'category_id' => 6, 'url' => 'blood-pressure.php', 'icon' => 'heart'],
            ['id' => 14, 'name' => 'Blood Glucose Curve', 'description' => 'Generate and track glucose curves', 'category_id' => 6, 'url' => 'glucose-curve.php', 'icon' => 'chart-line'],
            ['id' => 15, 'name' => 'Cushing\'s Diagnostic Tool', 'description' => 'Prediction tool for Cushing\'s Disease', 'category_id' => 6, 'url' => 'cushings-tool.php', 'icon' => 'microscope'],
            
            // Nutrition
            ['id' => 16, 'name' => 'Calorie Requirements', 'description' => 'Calculate nutritional needs', 'category_id' => 7, 'url' => 'calorie-requirements.php', 'icon' => 'utensils'],
            
            // Conversion
            ['id' => 17, 'name' => 'Unit Conversion', 'description' => 'Convert between different units', 'category_id' => 8, 'url' => 'unit-conversion.php', 'icon' => 'exchange-alt']
        ];
    }
    
    $query = "SELECT * FROM calculators ORDER BY name";
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

// Get the active category filter
$activeCategory = isset($_GET['category']) ? intval($_GET['category']) : 0;
$searchTerm = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';

// Get all categories and calculators
$categories = getCalculatorCategories();
$calculators = getCalculators();

// Filter calculators by category or search term if needed
$filteredCalculators = [];
foreach ($calculators as $calc) {
    $include = true;
    
    // Apply category filter if active
    if ($activeCategory > 0 && $calc['category_id'] != $activeCategory) {
        $include = false;
    }
    
    // Apply search filter if provided
    if ($searchTerm && stripos($calc['name'], $searchTerm) === false && stripos($calc['description'], $searchTerm) === false) {
        $include = false;
    }
    
    if ($include) {
        $filteredCalculators[] = $calc;
    }
}

// Get the page header
echo getHeader('Veterinary Calculators');
?>

<main>
    <div class="container">
        <div class="calculators-header">
            <h1><i class="fas fa-calculator"></i> Veterinary Calculators</h1>
            <p>Access all the veterinary calculators you need for your clinical practice</p>
        </div>
        
        <!-- Search and filter bar -->
        <div class="search-filter-bar">
            <form method="get" action="" class="search-form">
                <div class="search-input">
                    <input type="text" name="search" placeholder="Search calculators..." value="<?php echo $searchTerm; ?>">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </div>
                
                <?php if ($searchTerm): ?>
                <a href="calculators.php" class="clear-search"><i class="fas fa-times"></i> Clear Search</a>
                <?php endif; ?>
            </form>
            
            <div class="category-filters">
                <a href="calculators.php" class="category-btn <?php echo ($activeCategory == 0) ? 'active' : ''; ?>">
                    All Categories
                </a>
                
                <?php foreach ($categories as $category): ?>
                <a href="calculators.php?category=<?php echo $category['id']; ?>" 
                   class="category-btn <?php echo ($activeCategory == $category['id']) ? 'active' : ''; ?>">
                    <i class="fas fa-<?php echo $category['icon']; ?>"></i>
                    <?php echo $category['name']; ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Display calculators -->
        <?php if (empty($filteredCalculators)): ?>
        <div class="no-results">
            <h3>No calculators found</h3>
            <p>Try adjusting your search or filter criteria.</p>
        </div>
        <?php else: ?>
        <div class="calculator-grid">
            <?php foreach ($filteredCalculators as $calculator): ?>
            <a href="<?php echo $calculator['url']; ?>" class="calculator-item" data-category="<?php echo $calculator['category_id']; ?>">
                <i class="fas fa-<?php echo $calculator['icon']; ?>"></i>
                <h3><?php echo $calculator['name']; ?></h3>
                <p><?php echo $calculator['description']; ?></p>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        
        <!-- Help section with tips -->
        <div class="calculator-tips">
            <h3>Tips for Using VetCalcPro Calculators</h3>
            <div class="tips-container">
                <div class="tip-box">
                    <i class="fas fa-save"></i>
                    <h4>Save to Patient Records</h4>
                    <p>Each calculator allows you to save results directly to patient records for easy reference.</p>
                </div>
                <div class="tip-box">
                    <i class="fas fa-mobile-alt"></i>
                    <h4>Mobile-Friendly</h4>
                    <p>All calculators are fully responsive and work great on mobile devices at the point of care.</p>
                </div>
                <div class="tip-box">
                    <i class="fas fa-history"></i>
                    <h4>History Tracking</h4>
                    <p>Previous calculations are stored in your account history for easy reference.</p>
                </div>
                <div class="tip-box">
                    <i class="fas fa-print"></i>
                    <h4>Print Results</h4>
                    <p>Easily print calculation results or save them as PDFs for your records.</p>
                </div>
            </div>
        </div>
        
        <!-- Add new calculator link (for admin users) -->
        <div class="admin-links">
            <a href="admin/add-calculator.php" class="btn primary">
                <i class="fas fa-plus"></i> Add Custom Calculator
            </a>
        </div>
    </div>
</main>

<?php
// Get the page footer
echo getFooter();
?>
