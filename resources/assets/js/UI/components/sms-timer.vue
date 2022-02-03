<template>
    <div class="c-login__resend-otp-section">
        <div :class="{'hide': !timerRunning}" class="c-login__resend-otp-message js-phone-code-container">
            {{ countDownText }}
        </div>
        <a href="" type="btn btn-primary" :class="{'hide': timerRunning}"  @click.prevent="send">
            دریافت مجدد کد تایید
        </a>
    </div>
</template>
<script type="text/javascript">
export default {

    props: ['httpRequest', 'time', 'resendText','timerText', 'phone'],
    data: function () {
        return {
            countDownText: "",
            timerRunning: true,
            timer: 0,
            remainTime: 0
        }
    },
    mounted: function () {

        this.remainTime = this.time;
        if (this.remainTime > 0) {
            this.countDownText = this._formatTime();
        }else {
            // this.countDownText = this.resendText;
            this.timerRunning= false;
        }
        console.log(this.resendText);
        this._startTimer();
    },

    methods: {
        send: function () {
            if (this.timerRunning) {
                return;
            }
            if (this.httpRequest) {
                // this.countDownText = this.resendText;
                axios.post(this.httpRequest, {
                    'phone': this.phone,
                })
                    .then((response) => {
                        console.log(response);
                        if (response.data.status === "success") {
                            this.remainTime = response.data.data.remainTime;
                            this._startTimer();
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });

            }
        },
        _startTimer: function () {
            if (this.remainTime <= 0) {
                return;
            }
            this.countDownText = this._formatTime();
            this.timerRunning = true;
            console.log("starting...");
            console.log(this.remainTime);
            this.timer = window.setInterval(() => {
                if (this.remainTime === 1) {
                    this.timerRunning = false;
                    // this.countDownText = this.resendText;
                    clearInterval(this.timer);
                    // this.countDownText = "ارسال مجدد";
                    return;
                }
                this.remainTime = Number(this.remainTime) - 1;
                this.countDownText = this._formatTime();
            }, 1000);
        },
        _formatTime: function (){
            let seconds = (this.remainTime % 60).toLocaleString("en-US", {
                minimumIntegerDigits: 2,
                useGrouping: false,
            });
            let minutes = Math.floor(this.remainTime / 60).toLocaleString("en-US", {
                minimumIntegerDigits: 2,
                useGrouping: false,
            });
            console.log(this.remainTime,seconds,minutes);
            return this.timerText.replace('{}',minutes + ":" + seconds);
        }
    }

}
</script>

<style scoped lang="scss">
.sms-code:hover {
    cursor: pointer;
}
</style>