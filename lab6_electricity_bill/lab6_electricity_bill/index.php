<?php
/**
 * ELECTRICITY BILL CALCULATOR - LAB 6
 * Calculates electricity bill based on tiered pricing system
 * 
 * TIERED PRICING STRUCTURE:
 * 0-100 units: ₹5 per unit
 * 101-200 units: ₹7 per unit
 * 201-300 units: ₹10 per unit
 * Above 300 units: ₹15 per unit
 */

// Initialize variables
$units = 0;
$totalBill = 0;
$breakdown = [];
$error = "";

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get units consumed from form
    // trim() removes whitespace from beginning and end
    // Example: "  150  " becomes "150"
    $units = trim($_POST["units"]);
    
    // VALIDATION: Check if input is empty
    if (empty($units)) {
        // empty() returns TRUE if variable is:
        // - Empty string ""
        // - Zero "0" or 0
        // - NULL
        // - FALSE
        // - Empty array
        $error = "Please enter the number of units consumed.";
        
    } elseif (!is_numeric($units)) {
        // is_numeric() checks if value is number or numeric string
        // Returns FALSE for: "abc", "12.5.6", "12a"
        // Returns TRUE for: "123", 123, "12.5", 12.5
        // !is_numeric means "is NOT numeric"
        $error = "Please enter a valid number.";
        
    } elseif ($units < 0) {
        // Check for negative values
        // Negative units don't make sense in real world
        $error = "Units cannot be negative.";
        
    } else {
        // Input is valid - calculate bill
        // Convert to float for precise calculations
        // floatval() converts string to float number
        // Example: "150" becomes 150.0
        $units = floatval($units);
        
        // TIERED PRICING CALCULATION
        // We calculate bill in slabs (tiers)
        // Each slab has different rate
        
        // Slab 1: First 100 units @ ₹5 per unit
        if ($units <= 100) {
            // All units in first slab
            // Example: 50 units
            // Cost = 50 × 5 = ₹250
            $amount = $units * 5;
            $totalBill = $amount;
            $breakdown[] = [
                'slab' => '0-100 units',
                'units' => $units,
                'rate' => 5,
                'amount' => $amount
            ];
            // [] creates array
            // ['key' => 'value'] creates associative array
            // breakdown[] appends to array
            
        } elseif ($units <= 200) {
            // Units between 101-200
            // Example: 150 units
            // First 100 @ ₹5 = ₹500
            // Next 50 @ ₹7 = ₹350
            // Total = ₹850
            
            // First 100 units @ ₹5
            $amount1 = 100 * 5;
            $breakdown[] = [
                'slab' => '0-100 units',
                'units' => 100,
                'rate' => 5,
                'amount' => $amount1
            ];
            
            // Remaining units (101-200) @ ₹7
            $remaining = $units - 100;
            // Example: 150 - 100 = 50 units
            $amount2 = $remaining * 7;
            $breakdown[] = [
                'slab' => '101-200 units',
                'units' => $remaining,
                'rate' => 7,
                'amount' => $amount2
            ];
            
            $totalBill = $amount1 + $amount2;
            
        } elseif ($units <= 300) {
            // Units between 201-300
            // Example: 250 units
            // First 100 @ ₹5 = ₹500
            // Next 100 @ ₹7 = ₹700
            // Next 50 @ ₹10 = ₹500
            // Total = ₹1700
            
            // First 100 units @ ₹5
            $amount1 = 100 * 5;
            $breakdown[] = [
                'slab' => '0-100 units',
                'units' => 100,
                'rate' => 5,
                'amount' => $amount1
            ];
            
            // Next 100 units (101-200) @ ₹7
            $amount2 = 100 * 7;
            $breakdown[] = [
                'slab' => '101-200 units',
                'units' => 100,
                'rate' => 7,
                'amount' => $amount2
            ];
            
            // Remaining units (201-300) @ ₹10
            $remaining = $units - 200;
            $amount3 = $remaining * 10;
            $breakdown[] = [
                'slab' => '201-300 units',
                'units' => $remaining,
                'rate' => 10,
                'amount' => $amount3
            ];
            
            $totalBill = $amount1 + $amount2 + $amount3;
            
        } else {
            // Units above 300
            // Example: 350 units
            // First 100 @ ₹5 = ₹500
            // Next 100 @ ₹7 = ₹700
            // Next 100 @ ₹10 = ₹1000
            // Next 50 @ ₹15 = ₹750
            // Total = ₹2950
            
            // First 100 units @ ₹5
            $amount1 = 100 * 5;
            $breakdown[] = [
                'slab' => '0-100 units',
                'units' => 100,
                'rate' => 5,
                'amount' => $amount1
            ];
            
            // Next 100 units (101-200) @ ₹7
            $amount2 = 100 * 7;
            $breakdown[] = [
                'slab' => '101-200 units',
                'units' => 100,
                'rate' => 7,
                'amount' => $amount2
            ];
            
            // Next 100 units (201-300) @ ₹10
            $amount3 = 100 * 10;
            $breakdown[] = [
                'slab' => '201-300 units',
                'units' => 100,
                'rate' => 10,
                'amount' => $amount3
            ];
            
            // Remaining units (above 300) @ ₹15
            $remaining = $units - 300;
            $amount4 = $remaining * 15;
            $breakdown[] = [
                'slab' => 'Above 300 units',
                'units' => $remaining,
                'rate' => 15,
                'amount' => $amount4
            ];
            
            $totalBill = $amount1 + $amount2 + $amount3 + $amount4;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- UTF-8 character encoding supports all languages -->
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Responsive design meta tag for mobile devices -->
    
    <title>Electricity Bill Calculator - Lab 6</title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            /* box-sizing ensures padding is included in width */
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            /* Font stack - tries fonts in order */
            
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            /* Diagonal gradient background (purple shades) */
            /* 135deg = diagonal from top-left to bottom-right */
            
            min-height: 100vh;
            /* vh = viewport height (100vh = full screen height) */
            
            padding: 50px 20px;
            /* 50px top/bottom, 20px left/right */
            
            display: flex;
            /* Flexbox for centering */
            
            justify-content: center;
            /* Center horizontally */
            
            align-items: center;
            /* Center vertically */
        }
        
        .container {
            max-width: 800px;
            /* Maximum width constraint */
            
            width: 100%;
            /* Full width up to max-width */
            
            background: white;
            border-radius: 20px;
            /* Rounded corners */
            
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            /* Drop shadow for depth */
            /* 0: horizontal offset */
            /* 20px: vertical offset (shadow below) */
            /* 60px: blur radius */
            /* rgba(0,0,0,0.3): black at 30% opacity */
            
            padding: 40px;
            overflow: hidden;
            /* Hide overflow content */
        }
        
        h1 {
            color: #667eea;
            text-align: center;
            margin-bottom: 10px;
            font-size: 32px;
        }
        
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        
        .pricing-info {
            background: #e3f2fd;
            /* Light blue background */
            
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            border-left: 4px solid #2196f3;
            /* Blue left border accent */
        }
        
        .pricing-info h3 {
            color: #1565c0;
            margin-bottom: 15px;
            font-size: 18px;
        }
        
        .pricing-table {
            width: 100%;
            border-collapse: collapse;
            /* Removes gaps between table cells */
        }
        
        .pricing-table th,
        .pricing-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #90caf9;
        }
        
        .pricing-table th {
            background: #2196f3;
            color: white;
            font-weight: 600;
        }
        
        .pricing-table tr:last-child td {
            border-bottom: none;
            /* Remove border from last row */
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        label {
            display: block;
            /* Takes full width */
            
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 15px;
        }
        
        input[type="number"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
            /* Smooth transition for animations */
        }
        
        input:focus {
            /* Styles when input is clicked/active */
            
            outline: none;
            /* Remove default blue outline */
            
            border-color: #667eea;
            /* Purple border on focus */
            
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            /* Subtle glow effect */
        }
        
        .error {
            background: #ffebee;
            /* Light red background */
            
            color: #c62828;
            /* Dark red text */
            
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #c62828;
        }
        
        button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            /* Same gradient as body */
            
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            /* Hand cursor on hover */
            
            transition: all 0.3s ease;
        }
        
        button:hover {
            /* Styles on mouse hover */
            
            transform: translateY(-2px);
            /* Move up 2 pixels (lift effect) */
            
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            /* Stronger shadow */
        }
        
        button:active {
            /* Styles when being clicked */
            
            transform: translateY(0);
            /* Return to original position */
        }
        
        .result-section {
            margin-top: 30px;
            padding: 25px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        
        .total-bill {
            text-align: center;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .total-bill h2 {
            font-size: 18px;
            margin-bottom: 10px;
            opacity: 0.9;
            /* Slightly transparent */
        }
        
        .total-bill .amount {
            font-size: 48px;
            font-weight: 700;
            margin: 10px 0;
        }
        
        .breakdown-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .breakdown-table th,
        .breakdown-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .breakdown-table th {
            background: #667eea;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            /* SLAB → SLAB */
            font-size: 13px;
        }
        
        .breakdown-table tr:hover {
            background: #f5f5f5;
            /* Light gray on hover */
        }
        
        .breakdown-table .amount-col {
            text-align: right;
            /* Right-align numbers */
            font-weight: 600;
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>⚡ Electricity Bill Calculator</h1>
        <p class="subtitle">Lab 6: PHP Tiered Pricing System</p>
        
        <!-- PRICING INFORMATION TABLE -->
        <div class="pricing-info">
            <h3>📊 Tiered Pricing Structure</h3>
            <table class="pricing-table">
                <thead>
                    <tr>
                        <th>Slab</th>
                        <th>Units Range</th>
                        <th>Rate per Unit</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Slab 1</td>
                        <td>0 - 100 units</td>
                        <td>₹5.00</td>
                    </tr>
                    <tr>
                        <td>Slab 2</td>
                        <td>101 - 200 units</td>
                        <td>₹7.00</td>
                    </tr>
                    <tr>
                        <td>Slab 3</td>
                        <td>201 - 300 units</td>
                        <td>₹10.00</td>
                    </tr>
                    <tr>
                        <td>Slab 4</td>
                        <td>Above 300 units</td>
                        <td>₹15.00</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- ERROR MESSAGE (if validation fails) -->
        <?php if (!empty($error)): ?>
            <!-- !empty($error) = TRUE if error message exists -->
            <div class="error">
                ⚠️ <?php echo htmlspecialchars($error); ?>
                <!-- htmlspecialchars() prevents XSS attacks -->
                <!-- Converts < > & " ' to HTML entities -->
            </div>
        <?php endif; ?>
        
        <!-- CALCULATION FORM -->
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <!-- method="POST": Submit using POST (secure) -->
            <!-- action="<?php echo $_SERVER["PHP_SELF"]; ?>": Submit to same page -->
            <!-- $_SERVER["PHP_SELF"]: Current page filename -->
            
            <div class="form-group">
                <label for="units">Enter Units Consumed:</label>
                <input 
                    type="number" 
                    id="units" 
                    name="units" 
                    placeholder="e.g., 150" 
                    step="0.01"
                    value="<?php echo $units > 0 ? htmlspecialchars($units) : ''; ?>"
                    required>
                <!-- type="number": Only accepts numbers -->
                <!-- step="0.01": Allows decimals (150.5 units) -->
                <!-- value: Pre-fill with previous input after submit -->
                <!-- Ternary operator: condition ? value_if_true : value_if_false -->
                <!-- required: HTML5 validation (browser checks before submit) -->
            </div>
            
            <button type="submit">💰 Calculate Bill</button>
        </form>
        
        <!-- RESULT SECTION (shown only after calculation) -->
        <?php if ($totalBill > 0): ?>
            <!-- Display results only if bill was calculated -->
            
            <div class="result-section">
                <!-- TOTAL BILL DISPLAY -->
                <div class="total-bill">
                    <h2>Total Bill Amount</h2>
                    <div class="amount">₹<?php echo number_format($totalBill, 2); ?></div>
                    <!-- number_format($number, $decimals) formats number -->
                    <!-- Example: 1234.5 becomes "1,234.50" -->
                    <p style="opacity: 0.9;">for <?php echo number_format($units, 2); ?> units</p>
                </div>
                
                <!-- DETAILED BREAKDOWN TABLE -->
                <h3 style="color: #333; margin-bottom: 15px;">📋 Bill Breakdown</h3>
                <table class="breakdown-table">
                    <thead>
                        <tr>
                            <th>Slab</th>
                            <th>Units Used</th>
                            <th>Rate/Unit</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($breakdown as $item): ?>
                            <!-- foreach: Loop through each item in $breakdown array -->
                            <!-- $breakdown is array of arrays from calculation above -->
                            <!-- Each $item is: ['slab' => '...', 'units' => ..., etc.] -->
                            
                            <tr>
                                <td><?php echo htmlspecialchars($item['slab']); ?></td>
                                <!-- $item['slab']: Access 'slab' key from array -->
                                
                                <td><?php echo number_format($item['units'], 2); ?></td>
                                
                                <td>₹<?php echo number_format($item['rate'], 2); ?></td>
                                
                                <td class="amount-col">
                                    ₹<?php echo number_format($item['amount'], 2); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <!-- endforeach: Closes foreach loop -->
                        
                        <!-- TOTAL ROW -->
                        <tr style="background: #f0f0f0; font-weight: 600;">
                            <td colspan="3" style="text-align: right;">TOTAL:</td>
                            <!-- colspan="3": Merge 3 columns -->
                            
                            <td class="amount-col" style="font-size: 18px;">
                                ₹<?php echo number_format($totalBill, 2); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
