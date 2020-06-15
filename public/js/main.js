// Bloodhound is the typeahead.js suggestion engine
var ingredients = new Bloodhound({
    // Gets information from tags.json
    prefetch:{
        url:'ingredient/ingredients.json',
        cache: false
    },

    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('nom'),
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    
})
$('.tag-input').tagsinput({
    
    typeaheadjs: [{
        highlight: true,
    },
    {
        name: 'ingredients',
        display: 'nom',
        value: 'nom',
        source: ingredients,
        limit: 10, // This controls the number of suggestions displayed
    }]
})

