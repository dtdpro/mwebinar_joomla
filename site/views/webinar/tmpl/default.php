<h1 class="title uk-article-title mwebinar-category"><?php echo $this->webinar->category_title; ?></h1>
<h3 class="uk-text-bold mwebinar-title"><?php echo $this->webinar->name; ?></h3>
<div class="uk-text-center">
    <div id="mwebinar-page-title"></div>
	<?php if ($this->webinar->params->showprogress) { ?>
        <div id="mwebinar-page-progress"></div>
	<?php } ?>
    <div id="mwebinar-page-content"></div>
    <div id="mwebinar-page-content-end" style="display: none"><?php echo $this->webinar->content_end; ?></div>
    <div id="mwebinar-page-navigation" class="uk-button-group uk-margin-top uk-width-1-1"><button id="mwebinar-page-prev" class="uk-width-1-2 uk-button uk-button-large">Previous</button><button id="mwebinar-page-next" class="uk-width-1-2 uk-button uk-button-primary uk-button-large">Continue</button></div>

    <script type="text/javascript">
        var webinarData = <?php echo $this->webinarJSON; ?>;
        var webinarId = webinarData.id;
        var pageIndex = 0;
        var startPageIndex = 0;
        var numPages = webinarData.numPages;
        var completed = webinarData.completed;
        var sessionId = webinarData.sessionId;

        // Progress Bar
        if (webinarData.params.showprogress == 1) {
            var pbarHtml = "";
            if (webinarData.uikit == 2) {
                pbarHtml = '<div class="uk-progress"><div class="uk-progress-bar" style="width: 0%;"></div></div>';
            }
            if (webinarData.uikit == 3) {
                pbarHtml = '<progress id="mwebinar-page-progress-bar" class="uk-progress" value="0" max="100"></progress>';
            }
            jQuery('#mwebinar-page-progress').html(pbarHtml);
        }

        loadPage(pageIndex);

        function loadPage(pn) {
            jQuery('#mwebinar-page-content').empty();
            var pageContent = webinarData.pages[pn];
            var pageType = pageContent.type;

            // Set page title
            if (webinarData.params.showpagetitle == 1) jQuery("#mwebinar-page-title").html(pageContent.title);

            // Set button labels
            if (pageContent.nextpage.label != "") jQuery('#mwebinar-page-next').html(pageContent.nextpage.label); else jQuery('#mwebinar-page-next').html("Continue");
            if (pageContent.prevpage.label != "") jQuery('#mwebinar-page-prev').html(pageContent.prevpage.label); else jQuery('#mwebinar-page-prev').html("Previous");

            // Video Page
            if (pageType == 'video') {
                var html = '<div align="center"><video src="' + pageContent.video + '" poster="'+pageContent.still+'" width="768" height="432" id="mwebinar-page-video" controls="controls" style="outline:none;margin:0 auto;" class="uk-width-1-1" preload="auto" controls></video></div>';
                jQuery('#mwebinar-page-content').html(html);
                var videoPlayer = new MediaElementPlayer('#mwebinar-page-video', {
                    features: ['playpause', 'progress', 'current', 'duration', 'volume', 'fullscreen','universalgoogleanalytics'],
                    enablePluginSmoothing: true,
                    alwaysShowControls: false,
                    googleAnalyticsTitle: webinarData.title + " - " + pageContent.ordering
                });
                if (pageIndex != 0) {
                    jQuery('#mwebinar-page-content').show(400,function (e) {
                        videoPlayer.play();
                    });
                }

                jQuery("#mwebinar-page-video").on('ended', function (e) {
                    jQuery('#mwebinar-page-next').off('click');
                    jQuery('#mwebinar-page-prev').off('click');
                    jQuery("#mwebinar-page-video").off('ended');

                    if (pageContent.nextpage.id != 0) {
                        goPage(webinarData.pageMatch[pageContent.nextpage.id]);
                    } else if (pageIndex < (numPages-1)) {
                        nextPage();
                    } else {
                        endPage();
                    }
                });

                jQuery('#mwebinar-page-next').on('click', function(e) {
                    jQuery('#mwebinar-page-next').off('click');
                    jQuery('#mwebinar-page-prev').off('click');
                    jQuery("#mwebinar-page-video").off('ended');

                    videoPlayer.pause();

                    if (pageContent.nextpage.id != 0) {
                        goPage(webinarData.pageMatch[pageContent.nextpage.id]);
                    } else if (pageIndex < (numPages-1)) {
                        nextPage();
                    } else {
                        endPage();
                    }
                });
                jQuery('#mwebinar-page-prev').on('click', function(e) {
                    jQuery('#mwebinar-page-next').off('click');
                    jQuery('#mwebinar-page-prev').off('click');
                    jQuery("#mwebinar-page-video").off('ended');

                    videoPlayer.pause();

                    if (pageContent.prevpage.id != 0) {
                        goPage(webinarData.pageMatch[pageContent.prevpage.id]);
                    } else if (pageIndex == 0) {

                    } else {
                        prevPage();
                    }

                });

            }

            if (pageType == 'question') {
                var html = '<div class="uk-margin-top uk-text-left"><div class="uk-text-large uk-text-bold uk-margin-bottom">'+pageContent.question+'</div>';
                if (pageContent.questiontype == "multi") {
                    html += '<div class="uk-margin-bottom">Check all that apply</div>';
                }
                html += '<form name="webinar_question" id="webinar_question" action="" method="post" class="uk-form uk-form-stacked"><div class="uk-form-row"><div class="uk-form-controls">';
                jQuery.each(pageContent.options,function(i,v) {
                    if (pageContent.questiontype == "multi") {
                        html += '<input name="page_question[]" type="checkbox"';
                    } else {
                        html += '<input name="page_question" type="radio"';
                    }
                    html += ' value="' + v.name + '" id="qa_' + v.name + '"';
                    if (completed[pageIndex]) {
                        if (pageContent.questiontype == "multi") {
                            if (completed[pageIndex].indexOf(v.name) != -1) html += ' checked="checked"';
                        } else {
                            if (completed[pageIndex] == v.name) html += ' checked="checked"';
                        }
                        html += ' disabled="disabled"';
                    }
                    html += '> <label for="qa_'+ v.name+'">'+ v.title+'</label><br><br>';
                });
                html += '</div></div>';
                html += '<input type="hidden" name="page_sessionid" value="'+sessionId+'">';
                html += '</form>';
                html += '</div>';
                jQuery('#mwebinar-page-content').html(html);

                jQuery('#mwebinar-page-next').on('click', function(e) {
                    var qcheck = '';
                    if (pageContent.questiontype == "multi") {
                        qcheck += '#webinar_question input:checkbox:checked';
                    } else {
                        qcheck += '#webinar_question input:radio:checked';
                    }
                    if(jQuery(qcheck).length > 0 || completed[pageIndex]) {
                        var webinarqa = jQuery("#webinar_question").serialize();
                        var ansurl = "/index.php?option=com_mwebinar&view=webinar&task=answer&webinar="+webinarId+"&page="+pageContent.id;
                        if (!completed[pageIndex]) {
                            jQuery.post(ansurl, webinarqa);
                            if (pageContent.questiontype == "multi") {
                                var answeredValues = [];
                                jQuery(qcheck).each(function() {
                                    answeredValues.push(jQuery(this).val());
                                });
                                completed[pageIndex] = answeredValues;
                            } else {
                                completed[pageIndex] = jQuery('input[name=page_question]:checked', '#webinar_question').val();
                            }
                        }
                        jQuery('#mwebinar-page-next').off('click');
                        jQuery('#mwebinar-page-prev').off('click');

                        if (pageContent.nextpage.id != 0) {
                            goPage(webinarData.pageMatch[pageContent.nextpage.id]);
                        } else if (pageIndex < (numPages-1)) {
                            nextPage();
                        } else {
                            endPage();
                        }
                    } else {
                        UIkit.modal.alert("An answer is required");
                    }
                });
                jQuery('#mwebinar-page-prev').on('click', function(e) {
                    jQuery('#mwebinar-page-next').off('click');
                    jQuery('#mwebinar-page-prev').off('click');

                    if (pageContent.prevpage.id != 0) {
                        goPage(webinarData.pageMatch[pageContent.prevpage.id]);
                    } else if (pageIndex == 0) {

                    } else {
                        prevPage();
                    }
                });
            }

            if (pageType == 'field') {
                var html = '<div class="uk-margin-top uk-text-left"><div class="uk-text-large uk-text-bold uk-margin-bottom">'+pageContent.question+'</div>';
                html += '<form name="webinar_field" id="webinar_field" action="" method="post" class="uk-form uk-form-stacked">';
                jQuery.each(pageContent.fields,function(i,v) {
                    html += '<div class="uk-form-row">';
                    html += '<label for="qa_'+ v.name+'" class="uk-form-label">'+ v.title+'</label>';
                    html += '<div class="uk-form-controls">';
                    html += '<input class="uk-width-1-1" name="page_field['+v.name+']" type="text"';
                    if (completed[pageIndex][v.name]) html += 'value="' + completed[pageIndex][v.name] + '"';
                    html += 'id="qf_' + v.name + '"';
                    if (completed[pageIndex][v.name]) html += ' disabled="disabled"';
                    html += '></div></div>';
                });
                html += '<input type="hidden" name="page_sessionid" value="'+sessionId+'">';
                html += '</form>';
                html += '</div>';
                jQuery('#mwebinar-page-content').html(html);

                jQuery('#mwebinar-page-next').on('click', function(e) {
                    var answered = true;
                    jQuery.each(pageContent.fields,function(i,v) {
                        if (!jQuery("#qf_"+v.name).val()) answered = false;
                    });
                    if(answered || completed[pageIndex]) {
                        var webinarqa = jQuery("#webinar_field").serialize();
                        var ansurl = "/index.php?option=com_mwebinar&view=webinar&task=savefield&webinar="+webinarId+"&page="+pageContent.id;
                        if (!completed[pageIndex]) {
                            jQuery.post(ansurl, webinarqa);
                            var answeredValues = {};
                            jQuery.each(pageContent.fields,function(i,v) {
                                answeredValues[v.name] = jQuery("#qf_"+v.name).val();
                            });
                            completed[pageIndex] = answeredValues;
                        }
                        jQuery('#mwebinar-page-next').off('click');
                        jQuery('#mwebinar-page-prev').off('click');

                        if (pageContent.nextpage.id != 0) {
                            goPage(webinarData.pageMatch[pageContent.nextpage.id]);
                        } else if (pageIndex < (numPages-1)) {
                            nextPage();
                        } else {
                            endPage();
                        }
                    } else {
                        UIkit.modal.alert("An answer is required");
                    }
                });
                jQuery('#mwebinar-page-prev').on('click', function(e) {
                    jQuery('#mwebinar-page-next').off('click');
                    jQuery('#mwebinar-page-prev').off('click');

                    if (pageContent.prevpage.id != 0) {
                        goPage(webinarData.pageMatch[pageContent.prevpage.id]);
                    } else if (pageIndex == 0) {

                    } else {
                        prevPage();
                    }
                });
            }

            if (pageType == 'rating') {
                var html = '<div class="uk-margin-top uk-text-left"><div class="uk-text-large uk-text-bold uk-margin-bottom">'+pageContent.question+'</div>';
                html += '<form name="webinar_question" id="webinar_question" action="" method="post" class="uk-form uk-form-stacked"><div class="uk-form-row"><div class="uk-form-controls"><div class="uk-grid" uk-grid>';
                html += '<div class="uk-width-1-2 uk-text-left">'+pageContent.rateStart+'</div>';
                html += '<div class="uk-width-1-2 uk-text-right">'+pageContent.rateEnd+'</div>';
                jQuery.each(pageContent.options,function(i,v) {
                    html += '<div class="uk-width-1-5"><input name="page_question" type="radio" value="'+ v.name+'" id="qa_'+ v.name+'"';
                    if (completed[pageIndex]) html += ' disabled="disabled"';
                    if (completed[pageIndex] == v.name) html += ' checked="checked"';
                    html += '> <label for="qa_'+ v.name+'">'+ v.title+'</label></div>';
                });
                html += '</div></div></div>';
                html += '<input type="hidden" name="page_sessionid" value="'+sessionId+'">';
                html += '</form>';
                html += '</div>';
                jQuery('#mwebinar-page-content').html(html);

                jQuery('#mwebinar-page-next').on('click', function(e) {
                    if(jQuery('#webinar_question input:radio:checked').length > 0 || completed[pageIndex]) {
                        var webinarqa = jQuery("#webinar_question").serialize();
                        var ansurl = "/index.php?option=com_mwebinar&view=webinar&task=answer&webinar="+webinarId+"&page="+pageContent.id;
                        if (!completed[pageIndex]) jQuery.post(ansurl, webinarqa);
                        jQuery('#mwebinar-page-next').off('click');
                        jQuery('#mwebinar-page-prev').off('click');
                        completed[pageIndex] = jQuery('input[name=page_question]:checked', '#webinar_question').val();

                        if (pageContent.nextpage.id != 0) {
                            goPage(webinarData.pageMatch[pageContent.nextpage.id]);
                        } else if (pageIndex < (numPages-1)) {
                            nextPage();
                        } else {
                            endPage();
                        }
                    } else {
                        UIkit.modal.alert("An answer is required");
                    }
                });
                jQuery('#mwebinar-page-prev').on('click', function(e) {
                    jQuery('#mwebinar-page-next').off('click');
                    jQuery('#mwebinar-page-prev').off('click');

                    if (pageContent.prevpage.id != 0) {
                        goPage(webinarData.pageMatch[pageContent.prevpage.id]);
                    } else if (pageIndex == 0) {

                    } else {
                        prevPage();
                    }
                });

            }



            if (pageType == 'jumppage') {
                var html = '<div class="uk-margin-top uk-text-left"><div class="uk-text-large uk-text-bold uk-margin-bottom">'+pageContent.instructions+'</div>';
                jQuery.each(pageContent.jumppoints,function(i,v) {
                    html += '<button id="mwebinar-page-jump-'+i+'" class="uk-width-1-1 uk-button uk-button-primary uk-button-large" onclick="goPage(webinarData.pageMatch['+v.id+'])">'+v.title+'</button><br><br>';

                    jQuery('#mwebinar-page-jump-'+i).on('click', function(e) {
                        $('#mwebinar-page-next').off('click');
                        $('#mwebinar-page-prev').off('click');
                    });

                });
                html += '</div>';
                jQuery('#mwebinar-page-content').html(html);

                jQuery('#mwebinar-page-next').on('click', function(e) {
                    jQuery('#mwebinar-page-next').off('click');
                    jQuery('#mwebinar-page-prev').off('click');

                    if (pageContent.nextpage.id != 0) {
                        goPage(webinarData.pageMatch[pageContent.nextpage.id]);
                    } else if (pageIndex < (numPages-1)) {
                        nextPage();
                    } else {
                        endPage();
                    }
                });
                jQuery('#mwebinar-page-prev').on('click', function(e) {
                    jQuery('#mwebinar-page-next').off('click');
                    jQuery('#mwebinar-page-prev').off('click');

                    if (pageContent.prevpage.id != 0) {
                        goPage(webinarData.pageMatch[pageContent.prevpage.id]);
                    } else if (pageIndex == 0) {

                    } else {
                        prevPage();
                    }
                });
            }

            // Update progress bar
            if (webinarData.params.showprogress == 1) {
                if (webinarData.uikit == 2) {
                    jQuery(".uk-progress-bar").width(((pageIndex+1)/numPages)*100+"%");
                }
                if (webinarData.uikit == 3) {
                    var bar = document.getElementById('mwebinar-page-progress-bar');
                    bar.value = ((pageIndex+1)/numPages)*100;
                }
            }

        }

        function nextPage() {
            pageIndex = pageIndex + 1;
            loadPage(pageIndex);
        }

        function prevPage() {
            pageIndex = pageIndex - 1;
            loadPage(pageIndex);
        }


        function endPage() {
            jQuery(".uk-progress").hide();
            jQuery('#mwebinar-page-title').hide();
            jQuery('#mwebinar-page-content').hide();
            jQuery('#mwebinar-page-navigation').hide();
            jQuery('#mwebinar-page-content-end').show();
        }

        function goPage(id) {
            pageIndex=id;
            loadPage(id);
        }
    </script>
</div>
