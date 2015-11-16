jQuery(window).load(function(){
    altezza=jQuery("#video_campaign_figure").height();
    jQuery("#video_campaignfw_figure").css("height",altezza+"px");
    jQuery("#video_campaignfw_figure").css("width","100%");
    jQuery("#video_campaignfw_figure video").css("height",altezza+"px");
});

function playVideoCampaign(){
    video = document.getElementById('video_campaign');
    if (video.paused === false) {
        video.pause();
        jQuery('#stop_campaign').hide();
        jQuery('#play_campaign').show();
        flag2=true;
    } else {
        video_length_round=10;
        if (flag2==false) {
            video.load();
            // Ensure video is fully buffered before playing
            video.addEventListener("canplay", function() {
                this.removeEventListener("canplay", arguments.callee, false);
                if(Math.round(video.buffered.end(0)) >= 1){
                    // Video is already loaded
                    video.play();
                    jQuery('#video_campaign_figure').css("opacity","1");
                    jQuery('#ajax_loader_video2').hide();
                    jQuery('#stop_campaign').show();
                    jQuery('#play_campaign').hide();

                } else {
                    jQuery('#ajax_loader_video2').show();
                    jQuery('#video_campaign_figure').css("opacity","0.3");
                    jQuery('#stop_campaign').hide();
                    jQuery('#play_campaign').hide();
                    // Monitor video buffer progress before playing
                    video.addEventListener("progress", function() {
                        if(Math.round(video.buffered.end(0)) == video_length_round){
                            this.removeEventListener("progress", arguments.callee, false);
                            video.play();
                            jQuery('#video_campaign_figure').css("opacity","1");
                            jQuery('#ajax_loader_video2').hide();
                            jQuery('#stop_campaign').show();
                            jQuery('#play_campaign').hide();

                        }
                    }, false);
                }
            }, false);
        }
        else {
            video.play();
            jQuery('#stop_campaign').show();
            jQuery('#play_campaign').hide();
        }



    }
}

function playVideoCampaignFW(){
    video = document.getElementById('video_campaignfw');
    if (video.paused === false) {
        video.pause();
        jQuery('#stop_campaignfw').hide();
        jQuery('#play_campaignfw').show();
        flag=true;
    } else {
        video_length_round=10;
        if (flag==false) {
            jQuery('#ajax_loader_videofw').show();
            jQuery('#video_campaignfw_figure').css("opacity","0.3");
            jQuery('#stop_campaignfw').hide();
            jQuery('#play_campaignfw').hide();
            video.load();
            // Ensure video is fully buffered before playing
            video.addEventListener("canplay", function() {
                this.removeEventListener("canplay", arguments.callee, false);
                if(Math.round(video.buffered.end(0)) >= 1){
                    // Video is already loaded
                    video.play();
                    jQuery('#video_campaignfw_figure').css("opacity","1");
                    jQuery('#ajax_loader_videofw').hide();
                    jQuery('#stop_campaignfw').show();
                    jQuery('#play_campaignfw').hide();

                } else {
                    jQuery('#ajax_loader_videofw').show();
                    jQuery('#video_campaignfw_figure').css("opacity","0.3");
                    jQuery('#stop_campaignfw').hide();
                    jQuery('#play_campaignfw').hide();
                    // Monitor video buffer progress before playing
                    video.addEventListener("progress", function() {
                        alert(Math.round(video.buffered.end(0)));
                        if(Math.round(video.buffered.end(0)) == video_length_round){
                            this.removeEventListener("progress", arguments.callee, false);
                            video.play();
                            jQuery('#video_campaignfw_figure').css("opacity","1");
                            jQuery('#ajax_loader_videofw').hide();
                            jQuery('#stop_campaignfw').show();
                            jQuery('#play_campaignfw').hide();

                        }
                    }, false);
                }
            }, false);
        }
        else {
            video.play();
            jQuery('#stop_campaignfw').show();
            jQuery('#play_campaignfw').hide();
        }



    }
}

jQuery(document).ready(function(){
    video = document.getElementById('video_campaign');
    flag2=false;
    flag=false;


    jQuery('.effect-bubba #fig_video_campaign').on('click',function(){
        playVideoCampaign();
    });

    jQuery('.effect-bubba #fig_video_campaignfw').on('click',function(){
        playVideoCampaignFW();
    });

    jQuery('.effect-bubba #fig_video_ancona').on('click',function(){
        video = document.getElementById('video_ancona');
        if (video.paused === false) {
            video.pause();
            jQuery('#stop_ancona').hide();
            jQuery('#play_ancona').show();
        } else {
            video.play();
            jQuery('#stop_ancona').show();
            jQuery('#play_ancona').hide();
        }

    });

    jQuery('.effect-bubba #fig_video_jesi').on('click',function(){
        video = document.getElementById('video_jesi');
        if (video.paused === false) {
            video.pause();
            jQuery('#stop_jesi').hide();
            jQuery('#play_jesi').show();
        } else {
            video.play();
            jQuery('#stop_jesi').show();
            jQuery('#play_jesi').hide();
        }

    });

    jQuery('.effect-bubba #fig_video_macerata').on('click',function(){
        video = document.getElementById('video_macerata');
        if (video.paused === false) {
            video.pause();
            jQuery('#stop_macerata').hide();
            jQuery('#play_macerata').show();
        } else {
            video.play();
            jQuery('#stop_macerata').show();
            jQuery('#play_macerata').hide();
        }

    });

    jQuery('.effect-bubba #fig_video_sanbenedetto').on('click',function(){
        video = document.getElementById('video_sanbenedetto');
        if (video.paused === false) {
            video.pause();
            jQuery('#stop_sanbenedetto').hide();
            jQuery('#play_sanbenedetto').show();
        } else {
            video.play();
            jQuery('#stop_sanbenedetto').show();
            jQuery('#play_sanbenedetto').hide();
        }

    });

    jQuery('#video_campaign').on('ended',function(){
        jQuery('#stop_campaign').hide();
        jQuery('#play_campaign').show();
        jQuery('#video_campaign').load();
        flag2=true;
    });

    jQuery('#video_campaignfw').on('ended',function(){
        jQuery('#stop_campaignfw').hide();
        jQuery('#play_campaignfw').show();
        jQuery('#video_campaignfw').load();
        flag=true;
    });

    jQuery('#video_ancona').on('ended',function(){
        jQuery('#stop_ancona').hide();
        jQuery('#play_ancona').show();
        jQuery('#video_ancona').load();
    });

    jQuery('#video_jesi').on('ended',function(){
        jQuery('#stop_jesi').hide();
        jQuery('#play_jesi').show();
        jQuery('#video_jesi').load();
    });

    jQuery('#video_macerata').on('ended',function(){
        jQuery('#stop_macerata').hide();
        jQuery('#play_macerata').show();
        jQuery('#video_macerata').load();
    });

    jQuery('#video_sanbenedetto').on('ended',function(){
        jQuery('#stop_sanbenedetto').hide();
        jQuery('#play_sanbenedetto').show();
        jQuery('#video_sanbenedetto').load();
    });
});