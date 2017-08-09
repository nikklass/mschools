<style>
		*
        {
            margin:0;
            padding:0;
            font-family:Arial;
            /*font-size:10pt;*/
            color:#000;
        }
        body
        {
            width:100%;
            font-family:Arial;
            /*font-size:10pt;*/
            margin:0;
            padding:0;
        }
         
        p
        {
            margin:0;
            padding:0;
        }
         
        #wrapper
        {
            width:180mm;
            margin:0 15mm;
        }
         
        .page
        {
            height:297mm;
            width:210mm;
            page-break-after:always;
        }
 
        table
        {
            border-left: 1px solid #ccc;
            border-top: 1px solid #ccc;
             
            border-spacing:0;
            border-collapse: collapse; 
             
        }
         
        table td 
        {
            border-right: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            padding: 2mm;
        }
         
        table tr.heading, table tr td.heading
        {
            background:#d6d6d6;
        }
         
        h1.heading
        {
            font-size:14pt;
            color:#000;
            font-weight:normal;
        }
         
        h2.heading
        {
            font-size:9pt;
            color:#000;
            font-weight:normal;
        }
         
        hr
        {
            color:#ccc;
            background:#ccc;
        }
         
        #invoice_body
        {
            height: 149mm;
        }
         
        #invoice_body , #invoice_total
        {   
            width:100%;
        }
        #invoice_body table , #invoice_total table
        {
            width:100%;
            border-left: 1px solid #ccc;
            border-top: 1px solid #ccc;
     
            border-spacing:0;
            border-collapse: collapse; 
             
            margin-top:5mm;
        }
         
        #invoice_body table td , #invoice_total table td
        {
            text-align:center;
            /*font-size:9pt;*/
            border-right: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            padding:2mm 0;
        }
         
        #invoice_body table td.mono  , #invoice_total table td.mono
        {
            font-family:monospace;
            text-align:right;
            padding-right:3mm;
            /*font-size:10pt;*/
        }
         
        #footer
        {   
            width:180mm;
            margin:0 15mm;
            padding-bottom:3mm;
        }
        #footer table
        {
            width:100%;
            border-left: 1px solid #ccc;
            border-top: 1px solid #ccc;
             
            background:#eee;
             
            border-spacing:0;
            border-collapse: collapse; 
        }
        #footer table td
        {
            width:25%;
            text-align:center;
            font-size:9pt;
            border-right: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
        }
				
		.text-left {
			text-align: left;
		}
		.text-right {
			text-align: right;
		}
		.text-center {
			text-align: center;
		}
		.text-justify {
			text-align: justify;
		}
		.text-nowrap {
			white-space: nowrap;
		}
		.text-lowercase {
			text-transform: lowercase;
		}
		.text-uppercase {
			text-transform: uppercase;
		}
		.text-capitalize {
			text-transform: capitalize;
		}
		.text-muted {
			color: #777;
		}
		.text-primary {
			color: #337ab7;
		}
		a.text-primary:focus, a.text-primary:hover {
			color: #286090;
		}
		.text-success {
			color: #3c763d;
		}
		a.text-success:focus, a.text-success:hover {
			color: #2b542c;
		}
		.text-info {
			color: #31708f;
		}
		a.text-info:focus, a.text-info:hover {
			color: #245269;
		}
		.text-warning {
			color: #8a6d3b;
		}
		a.text-warning:focus, a.text-warning:hover {
			color: #66512c;
		}
		.text-danger {
			color: #a94442;
		}
		a.text-danger:focus, a.text-danger:hover {
			color: #843534;
		}
		table {
			background-color: transparent;
		}
		caption {
			color: #777;
			padding-bottom: 8px;
			padding-top: 8px;
			text-align: left;
		}
		th {
			text-align: left;
		}
		.table {
			margin-bottom: 20px;
			max-width: 100%;
			width: 100%;
		}
		.table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
			border-top: 1px solid #ddd;
			line-height: 1.42857;
			padding: 8px;
			vertical-align: top;
		}
		.table > thead > tr > th {
			border-bottom: 2px solid #ddd;
			vertical-align: bottom;
		}
		.table > caption + thead > tr:first-child > td, .table > caption + thead > tr:first-child > th, .table > colgroup + thead > tr:first-child > td, .table > colgroup + thead > tr:first-child > th, .table > thead:first-child > tr:first-child > td, .table > thead:first-child > tr:first-child > th {
			border-top: 0 none;
		}
		.table > tbody + tbody {
			border-top: 2px solid #ddd;
		}
		.table .table {
			background-color: #fff;
		}
		.table-condensed > tbody > tr > td, .table-condensed > tbody > tr > th, .table-condensed > tfoot > tr > td, .table-condensed > tfoot > tr > th, .table-condensed > thead > tr > td, .table-condensed > thead > tr > th {
			padding: 5px;
		}
		.table-bordered {
			border: 1px solid #ddd;
		}
		.table-bordered > tbody > tr > td, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > td, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > thead > tr > th {
			border: 1px solid #ddd;
		}
		.table-bordered > thead > tr > td, .table-bordered > thead > tr > th {
			border-bottom-width: 2px;
		}
		.table-striped > tbody > tr:nth-of-type(2n+1) {
			background-color: #f9f9f9;
		}
		.table-hover > tbody > tr:hover {
			background-color: #f5f5f5;
		}
		table col[class*="col-"] {
			display: table-column;
			float: none;
			position: static;
		}
		table td[class*="col-"], table th[class*="col-"] {
			display: table-cell;
			float: none;
			position: static;
		}
		.table > tbody > tr.active > td, .table > tbody > tr.active > th, .table > tbody > tr > td.active, .table > tbody > tr > th.active, .table > tfoot > tr.active > td, .table > tfoot > tr.active > th, .table > tfoot > tr > td.active, .table > tfoot > tr > th.active, .table > thead > tr.active > td, .table > thead > tr.active > th, .table > thead > tr > td.active, .table > thead > tr > th.active {
			background-color: #f5f5f5;
		}
		.table-hover > tbody > tr.active:hover > td, .table-hover > tbody > tr.active:hover > th, .table-hover > tbody > tr:hover > .active, .table-hover > tbody > tr > td.active:hover, .table-hover > tbody > tr > th.active:hover {
			background-color: #e8e8e8;
		}
		.table > tbody > tr.success > td, .table > tbody > tr.success > th, .table > tbody > tr > td.success, .table > tbody > tr > th.success, .table > tfoot > tr.success > td, .table > tfoot > tr.success > th, .table > tfoot > tr > td.success, .table > tfoot > tr > th.success, .table > thead > tr.success > td, .table > thead > tr.success > th, .table > thead > tr > td.success, .table > thead > tr > th.success {
			background-color: #dff0d8;
		}
		.table-hover > tbody > tr.success:hover > td, .table-hover > tbody > tr.success:hover > th, .table-hover > tbody > tr:hover > .success, .table-hover > tbody > tr > td.success:hover, .table-hover > tbody > tr > th.success:hover {
			background-color: #d0e9c6;
		}
		.table > tbody > tr.info > td, .table > tbody > tr.info > th, .table > tbody > tr > td.info, .table > tbody > tr > th.info, .table > tfoot > tr.info > td, .table > tfoot > tr.info > th, .table > tfoot > tr > td.info, .table > tfoot > tr > th.info, .table > thead > tr.info > td, .table > thead > tr.info > th, .table > thead > tr > td.info, .table > thead > tr > th.info {
			background-color: #d9edf7;
		}
		.table-hover > tbody > tr.info:hover > td, .table-hover > tbody > tr.info:hover > th, .table-hover > tbody > tr:hover > .info, .table-hover > tbody > tr > td.info:hover, .table-hover > tbody > tr > th.info:hover {
			background-color: #c4e3f3;
		}
		.table > tbody > tr.warning > td, .table > tbody > tr.warning > th, .table > tbody > tr > td.warning, .table > tbody > tr > th.warning, .table > tfoot > tr.warning > td, .table > tfoot > tr.warning > th, .table > tfoot > tr > td.warning, .table > tfoot > tr > th.warning, .table > thead > tr.warning > td, .table > thead > tr.warning > th, .table > thead > tr > td.warning, .table > thead > tr > th.warning {
			background-color: #fcf8e3;
		}
		.table-hover > tbody > tr.warning:hover > td, .table-hover > tbody > tr.warning:hover > th, .table-hover > tbody > tr:hover > .warning, .table-hover > tbody > tr > td.warning:hover, .table-hover > tbody > tr > th.warning:hover {
			background-color: #faf2cc;
		}
		.table > tbody > tr.danger > td, .table > tbody > tr.danger > th, .table > tbody > tr > td.danger, .table > tbody > tr > th.danger, .table > tfoot > tr.danger > td, .table > tfoot > tr.danger > th, .table > tfoot > tr > td.danger, .table > tfoot > tr > th.danger, .table > thead > tr.danger > td, .table > thead > tr.danger > th, .table > thead > tr > td.danger, .table > thead > tr > th.danger {
			background-color: #f2dede;
		}
		.table-hover > tbody > tr.danger:hover > td, .table-hover > tbody > tr.danger:hover > th, .table-hover > tbody > tr:hover > .danger, .table-hover > tbody > tr > td.danger:hover, .table-hover > tbody > tr > th.danger:hover {
			background-color: #ebcccc;
		}
		.table-responsive {
			min-height: 0.01%;
			overflow-x: auto;
		}
</style>