<div class="ui container page">
	<h1 class="ui dividing header">Admin Tools</h1>

	<div class="ui top attached tabular menu">
        <a data-tab="first" class="item active">
            Add a Medication
        </a>
        <a data-tab="second" class="item">
            Add a Manufacturer
        </a>
        <a data-tab="third" class="item">
            Add a Sale
        </a>
    </div>
        
    <!-- Add medication form -->
    <div data-tab="first" class="ui bottom attached tab segment active">
        <form class="ui form add_form" id="add_medication_form" method="POST">
            <div class="required field">
                <label>Medication Name</label>
                <input type="text" placeholder="Name" pattern="[a-zA-Z]+" name="medication_name">
            </div>
            <div class="required field">
                <label>Generic Equivalent</label>
                <input type="text" placeholder="" pattern="[a-zA-Z]+" name="medication_generic_equivalent">
            </div>
            <div class="required field">
                <label>Price</label>
                <input type="number" step="any" placeholder="In Euros" pattern="number" name="medication_price">
            </div>
            <div class="required field">
                <label>Initial Stock</label>
                <input type="number" placeholder="" pattern="number" name="medication_stock_available">
            </div>
            <div class="required field">
                <label>Manufacturer</label> 
                <select id="manufacturer_names" class="fetch_list" name="medication_manufacturer_name">
                </select>
            </div>
            <button class="ui primary submit button" type="submit" value="Submit">Submit</button>
            <button class="ui basic button" type="reset" value="Reset">Reset</button>
        </form>
    </div>

    <!-- Add manufacturer form -->
    <div data-tab="second" class="ui bottom attached tab segment">
        <form class="ui form add_form" id="add_manufacturer_form" method="POST">
            <div class="required field">
                <label>Manufacturer Name</label> 
                <input type="text" placeholder="" name="manufacturer_name">
            </div>
            <div class="required field">
                <label>Address</label> 
                <input type="text" placeholder="" name="manufacturer_address">
            </div>
            <div class="field">
                <label>Email</label> 
                <input type="text" placeholder="" type="email" name="manufacturer_email">
            </div>
            <button class="ui primary submit button" type="submit" value="Submit">Submit</button>
            <button class="ui basic button" type="reset" value="Reset">Reset</button>
        </form>
    </div>

    <!-- Add sale form -->
    <div data-tab="third" class="ui bottom attached tab segment">
        <form class="ui form add_form" id="add_sale_form" method="POST">
            <div class="required field">
                <label>Medication</label> 
                <select id="medication_names" class="fetch_list" name="sale_medication_name" placeholder="--">
                </select>
            </div>
            <div id = "sale_amount_field" class="required inline disabled field">
                <label>Amount</label> 
                <input type="number" placeholder="" name="sale_amount">
                <div id="stock_available_message" class="ui left pointing basic label hidden"></div>
            </div>
            <button id="add_sale_submit_button" class="ui primary submit button disabled" type="submit" value="Submit">Submit</button>
            <button class="ui basic button" type="reset" value="Reset">Reset</button>
        </form>
    </div>
</div>