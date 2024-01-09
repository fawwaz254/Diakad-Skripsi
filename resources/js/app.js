require('./bootstrap');


var selects = document.querySelectorAll('select#urutan');
var notify = document.getElementById('notification');
var values = [];

function disableOther(currentIndex) {
    console.log(values);
    for (var i = 0; i < selects.length; i++) {
        if (i !== currentIndex) {
            for (var j = 0; j < selects[i].options.length; j++) {
                if (values.includes(selects[i].options[j].value)) {
                    selects[i].options[j].disabled = true;
                } else {
                    selects[i].options[j].disabled = false;
                }
            }
        } else {
            for (var j = 0; j < selects[i].options.length; j++) {
                if (selects[i].options[j].value === selects[currentIndex].value) {
                		
                    selects[i].options[j].disabled = true;
                } 
                if(!values.includes(selects[i].options[j].value)){
                    selects[i].options[j].disabled = false;
                
                }
            }
        }
    }
}

function getOthers(current) {
    values = [];
    for (var i = 0; i < selects.length; i++) {
        if (selects[i].value !== 'null' && selects[i] !== current) {
            values.push(selects[i].value);
        }
    }
    return values;
}

function checkUnique() {
    if (this.value && getOthers(this).indexOf(this.value) > -1) {
        vex.dialog.alert('You already selected that')
        this.value = null;
    } else {
        vex.dialog.alert('')
        if(this.value !== 'null') values.push(this.value)
    }
    disableOther(Array.from(selects).indexOf(this));
}

for (var i = 0; i < selects.length; i++) {
    selects[i].onchange = checkUnique;
}

document.getElementById('submit').onclick = function () {
    var selectedValues = getOthers();
    console.log(selectedValues);
    if (selectedValues.length < 6) {
        vex.dialog.alert('Select all six')
        return false;
    }
    vex.dialog.alert('')
    return true;
};