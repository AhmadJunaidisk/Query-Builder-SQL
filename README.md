# Query-Builder-SQL
Query builder CRUD, sama seperti fitur laravel





# 1. Insert Query

Berfungsi untuk menambahkan data kedalam tabel database

*Contoh penggunaan:*

`Query::table(nama_table)->insert([kolom1,kolom2,kolom3],[data1,data2,data3])->push();`

# 2. Select Query

Digunakan untuk menampilkan, mengambil maupun memilah informasi dari database atau data dari satu tabel serta beberapa tabel dalam relasi.

*Contoh penggunaan:*

`Query::table(nama_table)->select([kolom1,kolom2,kolom3])->getall();`

# 3. Select Where Query

Digunakan untuk memfilter hasil SELECT dengan mengekstrak record yang memenuhi persyaratan tertentu

*Contoh penggunaan:*

`Query::table(nama_table)->select([kolom1,kolom2,kolom3])->where([kolom1 => data1])->getall();`


# 4. Select Like Query

Digunakan dalam klausa WHERE untuk mencari data dengan pola tertentu dalam kolom.

*Method ini juga menggunakan klausa where

*Contoh penggunaan:*

`Query::table(nama_table)->select([kolom1,kolom2,kolom3])->like([kolom1 => data1])->getall();`


# 5. Update Query

Digunakan dalam klausa WHERE untuk mencari data dengan pola tertentu dalam kolom.

*Method ini juga menggunakan klausa where

*Contoh penggunaan:*

`Query::table("login_history")
            ->update(
                ["kolom1","kolom2","kolom3"],
                ["data1", "data2", "data3"]
            )
            ->where(["kolom1" => "data1"])
            ->push();`

# Coming Soon

**6. Select Like order by Query**

**7. Select order by Query**

**8. insert where Query**

**9. delete Query**

**10. Right/left/natural/inner join query**

**11. Between query **







