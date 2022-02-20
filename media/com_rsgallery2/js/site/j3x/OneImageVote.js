/**-------------------------------------------------------------------------------------
 * OneImageVote.js
 * -------------------------------------------------------------------------------------
 * user clicks a button for rating. the ID part "..._n" tells the number.
 * the number will be extracted and set to a input variable. Then task
 * rating.rateSingleImage is assigned an the form is submitted
 -------------------------------------------------------------------------------------
 * RSGallery2 project copyright .... ToDo: see other *.js/*.ts
 *
 *
 *
 * -------------------------------------------------------------------------------------
 */

//* ToDo: pure vanilla ==> nothing special here

// document.addEventListener("DOMContentLoaded", function(event) {
jQuery(document).ready(function ($) {

    /* ToDo: do i need random var names ?  */
    let buttonStars = $('.btn_star');

    buttonStars.on('click', function (e) {

        console.log ("vote.01");
//        alert("jquery: " + jQuery.fn.jquery);
//        console.log ("vote.03");

        //--- voting value ---------------------------------

        let ratingValue = $(this).attr('id').substr(-1);
        console.log ("rateValue: " + ratingValue);
        console.log ("vote.03");

        let ratingInput = $( "input[name=rating]:first" );
        console.log ("vote.04");

        ratingInput.value = ratingValue;
        console.log ("vote.05");


        //--- set task ---------------------------------

        let taskInput = $( "input[name=task]:first" );
        console.log ("vote.06");

        taskInput.value = "rating.rateSingleImage";
        console.log ("vote.07");


        //--- limit start ---------------------------------

        // ToDo: check if not set already
        // ToDo:  what happens if pagination is not set ?
        // do i need paginationImgIdx

        // transfer actual pagination 'limitstart'
        // <input type="hidden" name="limitstart" value="2">
        let limitStartInput = $( "input[name=limitstart]:first" );
        console.log ("vote.08");

        let limitStart = limitStartInput.val();
        console.log ("vote.09");

        let paginationImgIdx = $( "input[name=paginationImgIdx]:first" );
        console.log ("vote.10");

        paginationImgIdx.value = limitStart;
        console.log ("vote.11");

        //--- submit form ---------------------------------

//        var form = document.getElementById('rsgVoteForm');
        let form = $(this.form);
        console.log ("vote.20");

        form.submit();
        console.log ("vote.21");

    });


});
