

<?php   
            $sum = 0;
            $chave = "";
            $chave1 = "";
            while($sum <= 11){
                $chave1 = rand(1000,(pow(10,4)))." "; 
                $chave .= $chave1;
            $sum++;
            }
            //$chave = chr($chave);
    echo $chave;        
?>


        