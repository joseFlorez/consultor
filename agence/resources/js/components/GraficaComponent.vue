<template>
    <div>
        <canvas id="myChart"></canvas>
    </div>
</template>

<script>
    export default {
        props: ['items', 'mes'],
        data () {
            return {
                sets: [],
                labels: [],
                media: [],
                meses: [
                    'janeiro',
    	            'fevereiro',
    	            'março',
    	            'abril',
    	            'maio',
                    'junho',
    	            'julho',
                    'agosto',
        	        'setembro',
                	'outubro',
        	        'novembro',
                    'dezembro'
                ]
            }
        },
        methods: {
            graficoData: function()
            {
                
                for(var x = 0; x < Number(this.mes); x++)
                {
                    this.labels.push(this.meses[x]); 
                }
                
                for(var i in this.items){
                    this.sets.push(
                        {
                            label: this.items[i].no_usuario,
                            data: this.receitaLiquida(this.labels, this.items[i].months),
                            backgroundColor: this.getColor()
                        }
                    );
                }
            },
            receitaLiquida: function(labels, months)
            {
                var result = [];
                for(var i in labels) {
                    var value = (months[labels[i]]) ? months[labels[i]].valor : 0;
                    result.push(value);
                }
                return result;
            },

            mediaCustoFixo: function()
            {
                var sum = 0;
                for(var i in this.items){
                    sum += this.items[i].brut_salario;
                }
                
                var media = (sum/this.items.length);
                
                for(var x = 0; x < this.labels.length; x++){
                    this.media.push(media);
                }
            },

            getColor()
            {
                var r = Math.round((Math.random()*255));
                var g = Math.round((Math.random()*200));
                var b = Math.round((Math.random()*150));
                return 'rgba('+r+','+g+','+b+',0.5)';
            }
        },
        mounted: function(){
            this.graficoData();
            this.mediaCustoFixo();
            var ctx = document.getElementById('myChart').getContext('2d');
	        var mixedChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    datasets: [
                        ...this.sets,
                        {
                            backgroundColor: 'rgba(255,255,255,0)',
                            borderColor: 'rgb(255,0,0)',
                            label: 'Custo Fixo Medio',
                            data: this.media,
                            // Changes this dataset to become a line
                            type: 'line'
                        }
                    ],
                    labels: this.labels
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                var label = data.datasets[tooltipItem.datasetIndex].label || '';

                                if (label) {
                                    label += ': ';
                                }
                                //label += Math.round(tooltipItem.yLabel * 100) / 100;
                                label += Intl.NumberFormat("pt-BR", {style: 'currency', currency: 'BRL'}).format(tooltipItem.yLabel);
                                return label;
                            }
                        }
                    }
                }
            });
        }
    }
</script>