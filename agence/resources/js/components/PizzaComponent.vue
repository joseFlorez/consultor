<template>
    <div>
        <canvas id="myChart"></canvas>
    </div>
</template>

<script>
    export default {
        props: ['items'],
        data () {
            return {
                labels: [],
                colors: [],
                valor: [],
                total: 0
            }
        },
        methods:
        {
            pizzaData: function()
            {
                for(var i in this.items){
                    this.labels.push(this.items[i].no_usuario),
                    this.colors.push(this.getColor()),
                    this.valor.push(this.getParticipao(this.items[i].months))
                }
            },
            getColor: function()
            {
                var r = Math.round((Math.random()*255));
                var g = Math.round((Math.random()*200));
                var b = Math.round((Math.random()*150));
                return 'rgba('+r+','+g+','+b+',0.5)';
            },
            getParticipao(months)
            {
                var value = 0;
                for(var x in months){
                    value += months[x].valor;
                }
                return value;
            }
        },
        mounted: function()
        {
            this.pizzaData();
            var ctx = document.getElementById('myChart').getContext('2d');
            var myPieChart = new Chart(ctx, {
                type: 'pie',
                data: {
                datasets: [{
                    data: this.valor,
                    backgroundColor: this.colors
                }],
                labels: this.labels
                },
                options: {
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                //data.datasets[0].data[tooltipItem.index]
                                var total = 0;
                                data.datasets[0].data.forEach(e => {
                                    total += e;
                                });
                                //var percentage = Math.round((data.datasets[0].data[tooltipItem.index]*100)/total);
                                var percentage = numeral((data.datasets[0].data[tooltipItem.index]*100)/total).format('0,0.00');
                                return  data.labels[tooltipItem.index] + ': ' + percentage +'%';
                            }
                        }
                    }
                }
            });
        }
    }
</script>