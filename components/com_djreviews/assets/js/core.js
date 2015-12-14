!function(t,i){window.DJReviewsCore=window.DJReviewsCore||{init:function(t){this.options=t,this.url=t.url,this.current_page=0,this.formId="djrv_review_form-"+this.options.object_id,this.formAnchorId="djrv-your-review-"+this.options.object_id,this.listId="djrv-reviews-list-"+this.options.object_id,this.ratingId="djrv-rating-full-"+this.options.object_id,this.ratingAvgId="djrv-rating-avg-"+this.options.object_id,this.addButtonClass="djrv_add_button",this.editButtonClass="djrv_edit_button",this.deleteButtonClass="djrv_delete_button",this.closeButtonClass="djrv_close_form_button",this.attachControls(),this.refreshAll(!0,!1)},attachControls:function(){var i=this;this.reviewForm=t("#"+this.formId),this.formAnchor=t("#"+this.formAnchorId),this.reviewsList=t("#"+this.listId),this.rating=t("#"+this.ratingId),this.ratingAvg=t("#"+this.ratingAvgId),this.addReviewButtons=t.find("."+this.addButtonClass),this.editReviewButtons=t.find("."+this.editButtonClass),this.deleteReviewButtons=t.find("."+this.deleteButtonClass),this.closeReviewButtons=t.find("."+this.closeButtonClass),this.paginationLinks=t("#"+this.listId).find(".djrv_pagination a.pagenav"),this.paginationLinks.length>0&&t(this.paginationLinks).each(function(e,n){t(n).unbind("click"),t(n).click(function(e){e.preventDefault();var o=t(n).attr("data-page");o&&(i.tweenOut(i.reviewsList),i.getReviews(o,!0,!0))})}),this.deleteReviewButtons.length>0&&t(this.deleteReviewButtons).each(function(e,n){t(n).unbind("click"),t(n).click(function(e){t(i.formAnchor).hide(),i.tweenOut(i.reviewsList),i.tweenOut(i.rating),i.tweenOut(i.ratingAvg),e.preventDefault(),t.ajax({type:"GET",async:!0,url:t(n).attr("href")}).done(function(){i.getReviews(i.current_page,!0,!1),i.getRating(!1),i.getRatingAvg(!1)})})}),this.reviewForm.length>0&&(this.addReviewButtons.length>0&&this.formAnchor.length>0&&t(this.addReviewButtons).each(function(e,n){t(n).unbind("click"),t(n).click(function(e){e.preventDefault(),t(i.formAnchor).hide(),i.loadEditForm(0,!0,!1)})}),this.closeReviewButtons.length>0&&this.formAnchor.length>0&&t(this.closeReviewButtons).each(function(e,n){t(n).unbind("click"),t(n).click(function(e){e.preventDefault(),t(i.formAnchor).hide(),t("html, body").animate({scrollTop:t(i.reviewsList).offset().top},20)})}),this.editReviewButtons.length>0&&this.formAnchor.length>0&&t(this.editReviewButtons).each(function(e,n){t(n).unbind("click"),t(n).click(function(e){e.preventDefault(),t(i.formAnchor).hide(),i.tweenOut(i.reviewsList),i.tweenOut(i.rating),i.tweenOut(i.ratingAvg),i.loadEditForm(t(n).attr("data-id"),!0,!1)})}),this.reviewForm.submit(function(e){e.preventDefault();var n=i.reviewForm.serialize();n+="&format=json",i.tweenOut(i.reviewsList),i.tweenOut(i.reviewForm),i.tweenOut(i.rating),i.tweenOut(i.ratingAvg),t.ajax({type:"POST",async:!1,url:i.url,data:n}).done(function(e){var n,o=!1;try{n=jQuery.parseJSON(e)}catch(s){n={status:0},o=!0,alert(e)}1!=n.status||o?o?(t(i.formAnchor).hide(),i.refreshAll(!1,!0)):(alert(n.error_message),i.tweenIn(i.reviewsList),i.tweenIn(i.reviewForm),i.tweenIn(i.rating),i.tweenIn(i.ratingAvg)):(t(i.formAnchor).hide(),i.refreshAll(!0,!0))}).fail(function(t){return 403==t.status?!1:401==t.status?!1:400==t.status?!1:void 0})}))},loadEditForm:function(e,n,o){var s=this,r="option=com_djreviews&view=reviewform&format=raw&id="+e;0==e&&(r+="&object_id="+s.options.object_id),t.ajax({type:"GET",async:o,url:s.url,data:r}).done(function(e){if(t(s.formAnchor).replaceWith(e),s.addToolTips(),s.attachControls(),typeof JFormValidator!=i)try{document.formvalidator=new JFormValidator}catch(o){}n&&(t(s.formAnchor).show(),s.addToolTips(),t("html, body").animate({scrollTop:t(s.formAnchor).offset().top},20),s.tweenIn(s.reviewsList),s.tweenIn(s.rating),s.tweenIn(s.ratingAvg))}).fail(function(t){return 403==t.status?!1:401==t.status?!1:400==t.status?!1:void 0})},getReviews:function(e,n,o){var s=this;e!=i&&e||(e=0),s.current_page=e;var r="option=com_djreviews&view=reviewslist&format=raw&id="+s.options.object_id+"&limitstart="+e;t.ajax({type:"GET",async:n,url:s.url,data:r}).done(function(i){t(s.reviewsList).replaceWith(i),s.addToolTips(),s.attachControls(),s.tweenIn(s.reviewsList),o&&t("html, body").animate({scrollTop:t(s.reviewsList).offset().top},20)}).fail(function(t){return 403==t.status?!1:401==t.status?!1:400==t.status?!1:void 0})},getRating:function(i){var e=this,n="option=com_djreviews&view=rating&format=raw&id="+e.options.object_id;t.ajax({type:"GET",async:i,url:e.url,data:n}).done(function(i){t(e.rating).replaceWith(i),e.addToolTips(),e.tweenIn(e.rating)}).fail(function(t){return 403==t.status?!1:401==t.status?!1:400==t.status?!1:void 0})},getRatingAvg:function(i){var e=this;if(0!=e.ratingAvg.length){var n="option=com_djreviews&view=rating&layout=avg&format=raw&id="+e.options.object_id;t.ajax({type:"GET",async:i,url:e.url,data:n}).done(function(i){t(e.ratingAvg).replaceWith(i),e.addToolTips(),e.tweenIn(e.ratingAvg)}).fail(function(t){return 403==t.status?!1:401==t.status?!1:400==t.status?!1:void 0})}},refreshAll:function(t,i){this.getRating(t),this.getRatingAvg(t),this.getReviews(0,t,i),this.loadEditForm(0,!1,!0)},tweenIn:function(i){t(i).length>0&&t(i).css({opacity:"1"})},tweenOut:function(i){t(i).length>0&&t(i).css({opacity:"0.3"})},addToolTips:function(){t(".djrv_tooltip").each(function(){var i=t(this).attr("title");if(i){var e=i.split("::",2);t(this).data("tip:title",e[0]),t(this).data("tip:text",e[1])}});new Tips(t(".djrv_tooltip").get(),{maxTitleChars:50,fixed:!1})}}}(jQuery);