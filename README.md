# Query-Builder-SQL
Query builder CRUD, sama seperti fitur laravel





# 1. Insert Query

Berfungsi untuk menambahkan data kedalam tabel database

*Contoh penggunaan:*

`Query::table(nama_table)->insert([kolom1,kolom2,kolom3],[data1,data2,data3])->push();`

![carbon (9)](https://user-images.githubusercontent.com/94750834/196995536-6b50ccd5-6861-48af-ad15-99fe995108d4.png)

# 2. Select Query

Digunakan untuk menampilkan, mengambil maupun memilah informasi dari database atau data dari satu tabel serta beberapa tabel dalam relasi.

*Contoh penggunaan (mengambil data yang dipilih):*

`Query::table(nama_table)->select([kolom1,kolom2,kolom3])->getall();`
![carbon (10)](https://user-images.githubusercontent.com/94750834/196995748-940927be-cdfe-4825-ab19-243d64e8dab6.png)


*Contoh penggunaan (mengambil semua data):*

`Query::table(nama_table)->select("*")->getall();`
![carbon (15)](https://user-images.githubusercontent.com/94750834/196997437-98562530-1534-44da-b22e-c20ec802361a.png)

# 3. Select Where Query

Digunakan untuk memfilter hasil SELECT dengan mengekstrak record yang memenuhi persyaratan tertentu

*Contoh penggunaan:*

`Query::table(nama_table)->select([kolom1,kolom2,kolom3])->where([kolom1 => data1])->getall();`
![carbon (11)](https://user-images.githubusercontent.com/94750834/196996011-de6a4e29-7f8c-4a56-b6fe-925bcc6c78b6.png)

# 4. Select Like Query

Digunakan dalam klausa WHERE untuk mencari data dengan pola tertentu dalam kolom.

*Method ini juga menggunakan klausa where

*Contoh penggunaan:*

`Query::table(nama_table)->select([kolom1,kolom2,kolom3])->like([kolom1 => data1])->getall();`
![carbon (12)](https://user-images.githubusercontent.com/94750834/196996184-a1a09247-e53b-40fe-bf2d-3505d2530d9b.png)


# 5. Update Query

Berfungsi untuk mengubah data yang ada di tabel database

*Contoh penggunaan:*

`Query::table("login_history")
            ->update(
                ["kolom1","kolom2","kolom3"],
                ["data1", "data2", "data3"]
            )
            ->where(["kolom1" => "data1"])
            ->push();`
            
            
![carbon (20)](https://user-images.githubusercontent.com/94750834/197398334-da0535c6-1c7e-4c8e-8ecd-3d47ef03ed43.png)

# 6. Inner Join Query

Berfungsi untuk meng-merge / menggabungkan 2 atau lebih data dari dalam tabel

*Contoh penggunaan:*

`Query::table("nama_table")   
                ->select("*")
                ->innerJoin("nama_table2", ["nama_table2.kolom" => "nama_table.kolom"])
                ->getall();`
                
![carbon (19)](https://user-images.githubusercontent.com/94750834/197398050-f304f4c3-01f6-4611-994e-06b939f6e812.png)
 
 
 # 7. Limit Query

Berfungsi untuk mengambil data berdasarkan limit

*Contoh penggunaan:*

`Query::table("pengguna")->select("*")->limit(1)->getall();`

![carbon (17)](https://user-images.githubusercontent.com/94750834/197397833-996b42d9-2906-4677-9cd1-f1d3505cdaba.png)


# Coming Soon

**8. Select Like order by Query**

**9. Select order by Query**

**10. insert where Query**

**11. delete Query**

**12. Right/left/natural query**

**13. Between query**
