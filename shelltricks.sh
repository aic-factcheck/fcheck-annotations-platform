#!/bin/bash
#SBATCH --time=4:00:00
#SBATCH --nodes=1 --ntasks-per-node=1 --cpus-per-task=4
#SBATCH --partition=cpufast
#SBATCH --mem=128G
#SBATCH --error=../logs/rest_page.%j.err
#SBATCH --out=../logs/rest_page.%j.out

ml PyTorch/1.5.1-fosscuda-2019b-Python-3.7.4

cd ../..

source venv/bin/activate
pwd
echo "PYTHONPATH=$PYTHONPATH"
echo "PATH=$PATH"
echo "PYTHONHOME=$PYTHONHOME"
echo "VIRTUAL_ENV=$VIRTUAL_ENV"
cd drchajan

XDG_RUNTIME_DIR=""
port=8601
node=$(hostname -s)
user=$(whoami)

echo -e "
MacOS or linux terminal command to create your ssh tunnel:
ssh -N -L ${port}:${node}:${port} ${user}@147.32.83.248

Use a Browser on your local machine to go to:
localhost:${port}  (prefix w/ https:// if using password)
"
conda activate ctk
export FDIR=/mnt/data/factcheck/CTK/par4
export ner_model="/mnt/data/factcheck/ufal/ner/czech-cnec2.0-140304-no_numbers.ner"
export db_name="${FDIR}/interim/ctk_filtered.db"
export kw_model="${FDIR}/index/ctk_filtered-tfidf-ngram=2-hash=16777216-tokenizer=simple.npz"
export sem_embeddings="${FDIR}/emb/embedded_pages_mbert_finetuned_best_ict_1.3_finetuned_ORDERED_BY_ID_NFC"
export sem_model="/mnt/data/factcheck/ict_pretrained_models/sentence-transformers/mbert_finetuned_best_ict_1.3"
export sem_faiss_index="PCA384,Flat"
export excludekw="souhrn;sport;kolo;fotbal;hokej;Volejbal;Atletika;Lyžování;Cyklistika;Tenis;stolní tenis;Olympijské;Avízo;TABULKA;UPOZORNĚNÍ;PROTEXT;Deník"
export PYTHONPATH=src:$PYTHONPATH
nohup python src/app_claimgen/rest_claimgen.py --ner_model ${ner_model} --db_name ${db_name} --kw_model ${kw_model} --sem_model ${sem_model} --sem_embeddings ${sem_embeddings} --sem_faiss_index ${sem_faiss_index} --excludekw "${excludekw}"
